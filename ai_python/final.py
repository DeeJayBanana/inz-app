from flask import Flask, request, jsonify
import threading
import os
import cv2
import math
from ultralytics import YOLO
from cut import cut_clips
# from draw_ellipse import draw_ellipse # Odkomentuj jeśli używasz
from notify_laravel import notify_laravel

app = Flask(__name__)

def run_analysis(video_uuid, video_path):
    notify_laravel(uuid=video_uuid, status="processing")

    cap = None
    try:
        print(f" Rozpoczynam analizę dla UUID: {video_uuid}")
        model_path = 'runs/detect/train4-pilka-nozna/weights/best.pt'
        model = YOLO(model_path)

        thresholds = {0: 0.01, 1: 0.20, 2: 0.20, 3: 0.05}
        cap = cv2.VideoCapture(video_path)

        if not cap.isOpened():
            print(f" Błąd: Nie można otworzyć pliku {video_path}")
            notify_laravel(None, None, None, video_uuid, status="failed")
            return

        fps = cap.get(cv2.CAP_PROP_FPS)
        total_frames = int(cap.get(cv2.CAP_PROP_FRAME_COUNT))

        track_history = {}
        SPEED_THRESHOLD = 14
        last_cut_time = -60
        COOLDOWN = 60
        count = 0

        while cap.isOpened():
            success, frame = cap.read()
            if not success:
                print(" DEBUG: Osiągnięto koniec pliku wideo.")
                break

            count += 1
            current_second = count / fps

            # Logowanie postępu co 200 klatek
            if count % 200 == 0:
                print(f" Analiza {video_uuid}: {count}/{total_frames} klatek ({(count/total_frames)*100:.1f}%)")

            if count % 5 == 0:
                # Usunięto stream=True dla stabilności wątku
                results = model.track(source=frame, tracker="bytetrack.yaml", persist=True,
                                      imgsz=640, verbose=False, conf=0.001)

                for r in results:
                    if r.boxes is None: continue
                    for box in r.boxes:
                        if box.id is None: continue

                        track_id = int(box.id[0])
                        class_id = int(box.cls[0])
                        conf = float(box.conf[0])

                        if conf >= thresholds.get(class_id, 0.25):
                            x1, y1, x2, y2 = map(int, box.xyxy[0])
                            cx, cy = int((x1 + x2) / 2), y2

                            is_sprinting = False
                            if track_id in track_history:
                                prev_cx, prev_cy = track_history[track_id]
                                distance = math.sqrt((cx - prev_cx) ** 2 + (cy - prev_cy) ** 2)
                                if distance > SPEED_THRESHOLD:
                                    is_sprinting = True

                            track_history[track_id] = (cx, cy)

                            # Detekcja sprintu (klasa 2)
                            if class_id == 2 and is_sprinting:
                                if current_second - last_cut_time > COOLDOWN:
                                    clip_filename = f"sprint_{int(current_second)}s.mp4"
                                    print(f" Wykryto sprint w {int(current_second)}s. Wycinanie...")

                                    # WYCINANIE (Upewnij się, że cut_clips nie blokuje wątku na stałe)
                                    print(f"DEBUG: Zaczynam wycinać klip: {clip_filename}")
                                    cut_clips(video_path, current_second, clip_filename)
                                    print(f"DEBUG: Powróciłem z wycinania klipu: {clip_filename}")

                                    # POWIADOMIENIE O KLIPIE
                                    notify_laravel(clip_filename, f"clips/{clip_filename}", current_second, video_uuid)
                                    last_cut_time = current_second

    except Exception as e:
        print(f" KRYTYCZNY BŁĄD AI: {e}")
        notify_laravel(None, None, None, video_uuid, status="failed")

    finally:
        if cap is not None:
            cap.release()

        print(f"--- FINAŁ: Wysyłam status 'completed' dla {video_uuid} ---")
        # Finalne powiadomienie - to zmienia status w Laravelu
        notify_laravel(None, None, None, video_uuid, status="completed")

@app.route('/analyze', methods=['POST'])
def analyze():
    data = request.json
    v_id = data.get('id')
    v_path = data.get('path')

    if not v_id or not v_path:
        return jsonify({"error": "Brak danych"}), 400

    # Uruchomienie w osobnym wątku
    thread = threading.Thread(target=run_analysis, args=(v_id, v_path))
    thread.start()

    return jsonify({"status": "processing", "message": "Analiza AI wystartowała"}), 202

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)
