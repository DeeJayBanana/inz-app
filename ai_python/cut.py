import subprocess
import os


def cut_clips(path, time_detection, name_out):
    start_t = max(0, time_detection - 10) #10s wstecz
    time = 55 #Czas trwania całości clipu

    video_dir = os.path.dirname(path)
    output_dir = os.path.join(video_dir, 'clips')

    if not os.path.exists(output_dir):
        os.makedirs(output_dir)

    full_output_path = os.path.join(output_dir, name_out)

    #Ustawienia do wycięcia FFMPEG'iem
    cmd = [
        'ffmpeg', '-y', '-ss', str(start_t), '-i', path,
        '-t', str(time), '-c', 'copy', full_output_path
    ]

    try:
        result = subprocess.run(
            cmd,
            capture_output=True,
            text=True,
            check=True
        )
        print(f"--- FFmpeg Success: {name_out} ---")
    except subprocess.CalledProcessError as e:
        print(f"--- FFmpeg Error: {e.stderr} ---")
