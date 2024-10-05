// server.js
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// Serve static files (like the HTML file)
app.use(express.static(__dirname));

// Socket.IO event handling
io.on('connection', (socket) => {
    console.log('A user connected');

    socket.on('quiz', (data) => {
        console.log('quiz event received');
        console.log(data);
        io.emit('quiz', data); // Broadcast the quiz event
    });

    socket.on('disconnect', () => {
        console.log('A user disconnected');
    });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
