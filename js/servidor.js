const express = require('express');
const cors = require('cors');
const app = express();

app.use(cors());
app.use(express.json());

const registros = []; // Array para armazenar os registros de eventos

app.post('/api/eventos', (req, res) => {
    const { usina, unidadeGeradora, tipoEvento, descricao } = req.body;

    const novoEvento = {
        usina,
        unidadeGeradora,
        tipoEvento,
        descricao,
        timestamp: new Date().toISOString()
    };

    registros.push(novoEvento);

    console.log('Novo evento registrado:', novoEvento);

    res.json({ success: true, evento: novoEvento });
});

app.get('/api/eventos', (req, res) => {
    res.json(registros);
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor rodando na porta ${PORT}`);
});
