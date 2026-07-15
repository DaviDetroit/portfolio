import os
import pymysql
import streamlit as st
from streamlit.errors import StreamlitSecretNotFoundError


def _get_config(key):
    try:
        return st.secrets.get(key, os.getenv(key))
    except StreamlitSecretNotFoundError:
        return os.getenv(key)


connection = pymysql.connect(
    host=_get_config("DB_HOST"),
    port=int(_get_config("DB_PORT")),
    user=_get_config("DB_USER"),
    password=_get_config("DB_PASSWORD"),
    database=_get_config("DB_NAME"),
    cursorclass=pymysql.cursors.DictCursor,
    charset="utf8mb4",
    autocommit=True,
)