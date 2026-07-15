import os
import pymysql
import streamlit as st


def _get_config(key):
    if key in st.secrets:
        return st.secrets[key]
    return os.getenv(key)


connection = pymysql.connect(
    host=_get_config("DB_HOST"),
    port=int(_get_config("DB_PORT")),
    user=_get_config("DB_USER"),
    password=_get_config("DB_PASSWORD"),
    database=_get_config("DB_NAME"),
)