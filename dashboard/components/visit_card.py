import streamlit as st

from utils.formatters import relative_time

BROWSER_ICONS = {
    "chrome": "fa-brands fa-chrome",
    "firefox": "fa-brands fa-firefox-browser",
    "safari": "fa-brands fa-safari",
    "edge": "fa-brands fa-edge",
    "opera": "fa-brands fa-opera",
}

OS_ICONS = {
    "windows": "fa-brands fa-windows",
    "macos": "fa-brands fa-apple",
    "mac os x": "fa-brands fa-apple",
    "ios": "fa-brands fa-apple",
    "android": "fa-brands fa-android",
    "linux": "fa-brands fa-linux",
}

DEVICE_ICONS = {
    "desktop": "fa-solid fa-desktop",
    "mobile": "fa-solid fa-mobile-screen-button",
    "tablet": "fa-solid fa-tablet-screen-button",
}


def _icon(mapping, value, fallback="fa-solid fa-circle-question"):
    return mapping.get(str(value).strip().lower(), fallback)


def visit_card(row):
    time = relative_time(row["visited_at"])

    browser_icon = _icon(BROWSER_ICONS, row["browser"])
    os_icon = _icon(OS_ICONS, row["os"])
    device_icon = _icon(DEVICE_ICONS, row["device"])

    st.markdown(f"""<div style="background:var(--surface-1, #161B22);border:1px solid var(--border, #21262D);border-radius:10px;padding:14px 16px;margin-bottom:10px;">
<div style="display:flex;justify-content:space-between;align-items:baseline;">
<span style="font-size:14px;font-weight:500;color:#E6EDF3;">{row["country"]} · {row["city"]}</span>
<span style="font-size:12px;color:#6E7681;">{time}</span>
</div>
<div style="margin-top:6px;font-size:12px;color:#8B949E;">
<i class="{browser_icon}"></i> {row["browser"]} &nbsp;·&nbsp; <i class="{os_icon}"></i> {row["os"]} &nbsp;·&nbsp; <i class="{device_icon}"></i> {row["device"]} &nbsp;·&nbsp; {row["page"]}
</div>
</div>""", unsafe_allow_html=True)