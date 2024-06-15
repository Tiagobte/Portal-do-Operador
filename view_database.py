import mysql.connector
import time

def connect_to_db():
    # Configuração da conexão
    config = {
        'user': 'root',
        'password': '5a2c1234',
        'host': 'localhost',
        'database': 'portal_operador'
    }
    return mysql.connector.connect(**config)

def fetch_and_display_data(cursor, table):
    cursor.execute(f"SELECT * FROM {table}")
    rows = cursor.fetchall()
    print(f"\nDados da tabela '{table}':")
    for row in rows:
        print(row)

def main():
    table = 'eventos'  # Substitua 'eventos' pelo nome da tabela que deseja visualizar
    conn = connect_to_db()
    cursor = conn.cursor()

    while True:
        fetch_and_display_data(cursor, table)
        time.sleep(5)  # Aguarde 5 segundos antes de atualizar a visualização

        # Limpar a tela (funciona em Unix e Windows)
        print("\033c", end="")  # Para sistemas Unix/Linux/Mac
        # print(chr(27) + "[2J")  # Descomente para Windows

    # Fechar a conexão (não será alcançado devido ao loop infinito)
    cursor.close()
    conn.close()

if __name__ == "__main__":
    main()
