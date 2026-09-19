document.addEventListener('DOMContentLoaded', async () => {
    const filtroDoctor = document.getElementById('filtroDoctor');
    const formCrear = document.getElementById('formCrear');
    const modalCrear = document.getElementById('modalCrear');
    const modalDetalle = document.getElementById('modalDetalle');
    const detalle = document.getElementById('detalleContenido');
    let citaActual = null;

    const pad = n => String(n).padStart(2, '0');
    const fmt = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:00`;
    const toInput = d => fmt(d).slice(0, 16).replace(' ', 'T');
    const opciones = lista => lista.map(x => `<option value="${x.id}">${x.nombre}</option>`).join('');

    const [doctores, pacientes] = await Promise.all([Api.doctores(), Api.pacientes()]);
    filtroDoctor.innerHTML = '<option value="">Todos los doctores</option>' + opciones(doctores.data);
    formCrear.doctor_id.innerHTML = opciones(doctores.data);
    formCrear.paciente_id.innerHTML = opciones(pacientes.data);

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        locale: 'es',
        initialView: 'timeGridWeek',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        slotMinTime: '07:00:00',
        slotMaxTime: '19:00:00',
        allDaySlot: false,
        height: 'auto',
        selectable: true,
        editable: true,

        events: async (info, success, failure) => {
            try {
                const params = { desde: fmt(info.start), hasta: fmt(info.end) };
                if (filtroDoctor.value) params.doctor_id = filtroDoctor.value;
                const res = await Api.citas(params);
                success(res.data.map(c => ({
                    id: c.id,
                    title: `${c.paciente.nombre} · ${c.doctor.nombre}`,
                    start: c.inicio,
                    end: c.fin,
                    backgroundColor: c.color,
                    borderColor: c.color,
                    editable: ['pendiente', 'confirmada'].includes(c.estado),
                })));
            } catch (e) {
                failure(e);
                alert(e.message);
            }
        },

        select: info => {
            let inicio = info.start, fin = info.end;
            if (info.allDay) {
                inicio = new Date(info.start); inicio.setHours(8, 0);
                fin = new Date(inicio); fin.setMinutes(30);
            }
            formCrear.reset();
            formCrear.inicio.value = toInput(inicio);
            formCrear.fin.value = toInput(fin);
            modalCrear.showModal();
        },

        eventClick: async info => {
            try {
                const res = await Api.cita(info.event.id);
                mostrarDetalle(res.data);
            } catch (e) {
                alert(e.message);
            }
        },

        eventDrop: reprogramar,
        eventResize: reprogramar,
    });

    async function reprogramar(info) {
        try {
            await Api.reprogramar(info.event.id, { inicio: fmt(info.event.start), fin: fmt(info.event.end) });
            calendar.refetchEvents();
        } catch (e) {
            alert(e.message);
            info.revert();
        }
    }

    function mostrarDetalle(c) {
        citaActual = c;
        detalle.innerHTML = `
            <p><b>Paciente:</b> ${c.paciente.nombre}</p>
            <p><b>Doctor:</b> ${c.doctor.nombre}</p>
            <p><b>Inicio:</b> ${c.inicio.replace('T', ' ')}</p>
            <p><b>Fin:</b> ${c.fin.replace('T', ' ')}</p>
            <p><b>Motivo:</b> ${c.motivo}</p>
            <p><b>Estado:</b> <span style="color:${c.color};font-weight:bold">${c.estado}</span></p>`;
        const visibles = {
            confirmada: c.estado === 'pendiente',
            atendida: c.estado === 'confirmada',
            cancelada: ['pendiente', 'confirmada'].includes(c.estado),
        };
        document.querySelectorAll('[data-estado]').forEach(b => {
            b.style.display = visibles[b.dataset.estado] ? '' : 'none';
        });
        modalDetalle.showModal();
    }

    document.querySelectorAll('[data-estado]').forEach(btn => {
        btn.addEventListener('click', async () => {
            try {
                await Api.cambiarEstado(citaActual.id, btn.dataset.estado);
                modalDetalle.close();
                calendar.refetchEvents();
            } catch (e) {
                alert(e.message);
            }
        });
    });

    formCrear.addEventListener('submit', async e => {
        e.preventDefault();
        const f = new FormData(formCrear);
        try {
            await Api.crear({
                paciente_id: Number(f.get('paciente_id')),
                doctor_id: Number(f.get('doctor_id')),
                inicio: f.get('inicio').replace('T', ' ') + ':00',
                fin: f.get('fin').replace('T', ' ') + ':00',
                motivo: f.get('motivo'),
            });
            modalCrear.close();
            calendar.refetchEvents();
        } catch (err) {
            alert(err.message);
        }
    });

    filtroDoctor.addEventListener('change', () => calendar.refetchEvents());
    calendar.render();
});