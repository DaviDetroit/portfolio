import streamlit as st

def visit_card(row):

    st.markdown(f"""
<div style="
background:#161B22;
border:1px solid #30363D;
border-radius:14px;
padding:18px;
margin-bottom:14px;
">

<div style="
display:flex;
justify-content:space-between;
font-size:18px;
">

<b>🌎 {row["country"]} • {row["city"]}</b>

<span style="color:#94A3B8">
{row["visited_at"]}
</span>

</div>

<br>

💻 {row["browser"]}

&nbsp;&nbsp;&nbsp;

🖥️ {row["os"]}

&nbsp;&nbsp;&nbsp;

📱 {row["device"]}

<br><br>

<b>📄 {row["page"]}</b>

</div>
""", unsafe_allow_html=True)