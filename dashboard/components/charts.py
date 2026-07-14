import plotly.express as px


def line_chart(df):

    fig = px.line(
        df,
        x="dia",
        y="visitas",
        markers=True
    )

    fig.update_traces(
        line=dict(width=4),
        marker=dict(size=8)
    )

    fig.update_layout(

        template="plotly_dark",

        height=420,

        margin=dict(
            l=20,
            r=20,
            t=30,
            b=20
        ),

        paper_bgcolor="#0E1117",
        plot_bgcolor="#0E1117",

        xaxis_title="",
        yaxis_title="Visitas",

        hovermode="x unified",

        font=dict(size=14)

    )

    return fig

def pie_chart(df, names, values, title):

    fig = px.pie(
        df,
        names=names,
        values=values,
        hole=.55
    )

    fig.update_traces(
        textinfo="percent+label"
    )

    fig.update_layout(
        template="plotly_dark",
        height=340,
        title=title,
        showlegend=False,
        margin=dict(
            l=10,
            r=10,
            t=40,
            b=10
        ),
        paper_bgcolor="#0E1117",
        plot_bgcolor="#0E1117"
    )

    return fig