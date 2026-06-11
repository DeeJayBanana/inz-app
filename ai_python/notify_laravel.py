import requests

def notify_laravel(clip_name=None, clip_path=None, timestamp=None, uuid=None, status=None):
    url = "http://laravel_app:8000/api/save-clip"

    data = {
        "uuid": uuid,
        "filename": clip_name,
        "path": clip_path,
        "start_time": timestamp,
        "label": "sprint",
        "status": status
    }

    # Usuwamy z paczki klucze, które są None (opcjonalnie, dla czystości)
    data = {k: v for k, v in data.items() if v is not None}

    try:
        response = requests.post(url, data=data, timeout=10)
        return response.status_code
    except Exception as e:
        print(f"Błąd połączenia z Laravelem: {e}")


