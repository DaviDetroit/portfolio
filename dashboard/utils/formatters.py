from datetime import datetime


def relative_time(date):

    now = datetime.now()
    diff = now - date
    seconds = diff.total_seconds()

    if seconds < 0:
        seconds = 0

    if seconds < 60:
        return "Agora mesmo"

    if seconds < 3600:
        return f"há {int(seconds // 60)} minutos"

    today = now.date()
    visit_day = date.date()

    if visit_day == today:
        return f"Hoje às {date.strftime('%H:%M')}"

    if (today - visit_day).days == 1:
        return f"Ontem às {date.strftime('%H:%M')}"

    return date.strftime("%d/%m/%Y %H:%M")