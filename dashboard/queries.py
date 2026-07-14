import pandas as pd
from database import connection


def execute_procedure(name):
    with connection.cursor() as cursor:
        cursor.execute(f"CALL {name}()")

        rows = cursor.fetchall()

        connection.commit()

    return pd.DataFrame(rows)


def total_visits():
    return execute_procedure("sp_total_visitas")


def unique_visitors():
    return execute_procedure("sp_visitantes_unicos")


def visits_by_day():
    return execute_procedure("sp_visitas_por_dia")


def browsers():
    return execute_procedure("sp_navegadores")


def operating_systems():
    return execute_procedure("sp_sistemas_operacionais")


def devices():
    return execute_procedure("sp_dispositivos")


def countries():
    return execute_procedure("sp_paises")


def pages():
    return execute_procedure("sp_paginas_visitadas")


def last_visits():
    return execute_procedure("sp_ultimas_visitas")