const Api = {
    async request(url, options = {}) {
        const res = await fetch('/api' + url, {
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            ...options,
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const detalle = data.errors ? '\n' + Object.values(data.errors).flat().join('\n') : '';
            throw new Error((data.message || 'Error ' + res.status) + detalle);
        }
        return data;
    },
    citas(params) { return this.request('/citas?' + new URLSearchParams(params)); },
    cita(id) { return this.request('/citas/' + id); },
    crear(body) { return this.request('/citas', { method: 'POST', body: JSON.stringify(body) }); },
    reprogramar(id, body) { return this.request('/citas/' + id, { method: 'PUT', body: JSON.stringify(body) }); },
    cambiarEstado(id, estado) { return this.request('/citas/' + id + '/estado', { method: 'PATCH', body: JSON.stringify({ estado }) }); },
    doctores() { return this.request('/doctores'); },
    pacientes() { return this.request('/pacientes'); },
};