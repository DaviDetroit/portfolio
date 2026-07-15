import pandas as pd
from database import connection


def execute_procedure(name, params=None):
    with connection.cursor() as cursor:
        if params:
            cursor.execute(f"CALL {name}(%s)", params)
        else:
            cursor.execute(f"CALL {name}()")

        rows = cursor.fetchall()

        while cursor.nextset():
            pass

        connection.commit()

    return pd.DataFrame(rows)


def total_visits():
    return execute_procedure("sp_total_visitas")


def unique_visitors():
    return execute_procedure("sp_visitantes_unicos")


def visits_by_day(days=30):
    return execute_procedure("sp_visitas_periodo", (days,))


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