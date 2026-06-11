import cv2

def draw_ellipse(frame, x1, y1, x2, y2, color):
    center_x = int((x1 + x2) / 2)
    center_y = y2
    width = x2 - x1
    cv2.ellipse(
        frame,
        center=(center_x, center_y),
        axes=(int(width * 0.5), int(width * 0.25)),
        angle=0.0,
        startAngle=-45,
        endAngle=235,
        color=color,
        thickness=2,
        lineType=cv2.LINE_AA
    )