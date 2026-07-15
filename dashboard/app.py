import streamlit as st
from components.charts import line_chart
from components.charts import (
    line_chart,
    pie_chart
)
from components.cards import metric_card
from datetime import datetime
from components.insights import insight
from components.visit_card import visit_card
from queries import (
    total_visits,
    unique_visitors,
    browsers,
    operating_systems,
    devices,
    visits_by_day,
    countries,
    pages,
    last_visits
)

st.set_page_config(
    page_title="Dashboard | Portfólio",
    page_icon="📊",
    layout="wide"
)

st.markdown("""
<style>

.block-container{

    padding-top:2rem;
    padding-bottom:2rem;

}

</style>
""", unsafe_allow_html=True)

st.markdown("""
<h1 style="margin-bottom:0;">
📊 Portfolio Analytics
</h1>

<p style="color:#94A3B8;font-size:18px;margin-top:4px;">
Dashboard em tempo real do meu portfólio
</p>
""", unsafe_allow_html=True)

col1, col2, col3 = st.columns(3)

with col1:
    st.success("🟢 Banco conectado")

with col2:
    st.info("🚀 Railway")

with col3:
    st.info("🐬 MySQL")

st.caption(
    f"Última atualização: {datetime.now().strftime('%d/%m/%Y %H:%M:%S')}"
)

# ==========================
# CARDS
# ==========================

col1, col2, col3, col4 = st.columns(4)

with col1:
    metric_card(
        "Visitas",
        total_visits()["total"][0],
        "👁️"
    )

with col2:
    metric_card(
        "Visitantes",
        unique_visitors()["total"][0],
        "👤"
    )

with col3:
    metric_card(
        "Países",
        len(countries()),
        "🌎"
    )

with col4:
    metric_card(
        "Páginas",
        len(pages()),
        "📄"
    )

st.divider()

left, right = st.columns([3, 1])

with left:
    st.subheader("📈 Visitas por dia")

    days = st.selectbox(
        "Período",
        [7, 15, 30, 90],
        index=2
    )

    st.plotly_chart(
        line_chart(
            visits_by_day(days)
        ),
        use_container_width=True
    )

with right:

    st.subheader("🌎 Resumo")

    st.metric(
        "Navegadores",
        len(browsers())
    )

    st.metric(
        "Dispositivos",
        len(devices())
    )

st.divider()




st.subheader("📊 Estatísticas")

col1, col2 = st.columns(2)

with col1:

    st.plotly_chart(
        pie_chart(
            browsers(),
            "browser",
            "total",
            "Navegadores"
        ),
        use_container_width=True
    )

with col2:

    st.plotly_chart(
        pie_chart(
            operating_systems(),
            "os",
            "total",
            "Sistemas Operacionais"
        ),
        use_container_width=True
    )

col3, col4 = st.columns(2)

with col3:

    st.plotly_chart(
        pie_chart(
            devices(),
            "device",
            "total",
            "Dispositivos"
        ),
        use_container_width=True
    )

with col4:

    st.plotly_chart(
        pie_chart(
            countries(),
            "country",
            "total",
            "Países"
        ),
        use_container_width=True
    )

st.divider()
st.subheader("💡 Insights")

browser = browsers().iloc[0]

insight(
    "🌐",
    "Navegador mais utilizado",
    f"{browser['browser']} representa {browser['total']} visitas.",
    "#3B82F6"
)

os = operating_systems().iloc[0]

insight(
    "💻",
    "Sistema Operacional",
    f"{os['os']} foi utilizado em {os['total']} acessos.",
    "#22C55E"
)

device = devices().iloc[0]

insight(
    "📱",
    "Dispositivo mais usado",
    f"{device['device']} representa {device['total']} visitas.",
    "#F59E0B"
)

country = countries().iloc[0]

insight(
    "🌎",
    "País com mais acessos",
    f"{country['country']} possui {country['total']} visitas.",
    "#EF4444"
)

st.divider()

st.subheader("🕒 Últimas visitas")

for _, row in last_visits().iterrows():
    visit_card(row)

