const express = require('express');
const mysql = require('mysql');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '5a2c1234', // Verifique se a senha está correta
    database: 'portal_operador'
});


connection.connect((err) => {
    if (err) throw err;
    console.log('Conectado ao banco de dados MySQL');
});

// Middleware para analisar corpos de solicitação JSON
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Rota para lidar com o envio do formulário
app.post('/api/eventos', (req, res) => {
    const { usina, unidadeGeradora, tipoEvento, descricao } = req.body;

    // Obter a data e hora atuais
    const dataAtual = new Date();
    const data = dataAtual.toISOString().split('T')[0]; // Obtém a data no formato 'YYYY-MM-DD'
    const hora = dataAtual.toTimeString().split(' ')[0]; // Obtém a hora no formato 'HH:MM:SS'

    const evento = { usina, unidadeGeradora, tipoEvento, descricao, data, hora };

    // Inserir o evento no banco de dados
    const query = 'INSERT INTO eventos SET ?';
    connection.query(query, evento, (err, result) => {
        if (err) {
            console.error('Erro ao inserir evento no banco de dados:', err);
            res.status(500).send({ success: false, message: 'Erro interno do servidor' });
            return;
        }
        console.log('Evento inserido no banco de dados com sucesso');
        res.status(201).send({ success: true, message: 'Evento inserido no banco de dados com sucesso' });
    });
});

// Iniciar o servidor
app.listen(port, () => {
    console.log(`Servidor rodando em http://localhost:${port}`);
});
