import html
import streamlit as st


def insight(icon, title, text, color="#3B82F6"):
    safe_icon = html.escape(str(icon))
    safe_title = html.escape(str(title))
    safe_text = html.escape(str(text))

    card_html = f"""
    <div style="
        background:#161B22;
        border-left:5px solid {color};
        border-radius:12px;
        padding:18px;
        margin-bottom:12px;
        box-sizing:border-box;
    ">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <span style="font-size:1.1rem;">{safe_icon}</span>
            <h4 style="margin:0; color:white; font-size:1rem;">
                {safe_title}
            </h4>
        </div>

        <p style="
            color:#C9D1D9;
            margin:0;
            line-height:1.5;
        ">
            {safe_text}
        </p>
    </div>
    """

    st.components.v1.html(card_html, height=140, scrolling=False)