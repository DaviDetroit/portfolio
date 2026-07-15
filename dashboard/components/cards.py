import streamlit as st


def metric_card(title, value, icon):

    st.markdown(f"""
    <div style="
        background:#161B22;
        border:1px solid #30363D;
        border-radius:18px;
        padding:22px;
        text-align:center;
        box-shadow:0 0 20px rgba(59,130,246,.12);
    ">

<h3 style="
margin:0;
color:#8B949E;
font-weight:500;
">

{icon} {title}

</h3>

<h1 style="
margin-top:18px;
margin-bottom:0;
font-size:42px;
color:white;
">

{value}

</h1>

</div>
""",
unsafe_allow_html=True)