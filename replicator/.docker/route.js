function ucfirst(s) {
    return (s[0].toUpperCase() + s.slice(1))
}

function route(stream, eventType, data, meta) {
    return {
        topic: eventType + ':' + ucfirst(stream),
        partitionKey: stream
    }
}