import streamlit as st
from components.charts import line_chart
from components.charts import (
    line_chart,
    pie_chart
)

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

st.title("📊 Dashboard do Portfólio")
st.caption("Analytics em tempo real")


# ==========================
# CARDS
# ==========================

col1, col2, col3, col4 = st.columns(4)

with col1:
    st.metric(
        "👁️ Visitas",
        int(total_visits()["total"][0])
    )

with col2:
    st.metric(
        "👤 Visitantes",
        int(unique_visitors()["total"][0])
    )

with col3:
    st.metric(
        "🌎 Países",
        len(countries())
    )

with col4:
    st.metric(
        "📄 Páginas",
        len(pages())
    )

st.divider()

left, right = st.columns([3, 1])

with left:
    st.subheader("📈 Visitas por dia")

    st.plotly_chart(
        line_chart(visits_by_day()),
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
    