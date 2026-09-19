<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agenda de Citas Médicas</title>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/es.global.min.js"></script>
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; padding: 16px; background: #f5f7fa; }
        header { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        h1 { font-size: 1.4rem; margin: 0; }
        .leyenda span { display: inline-flex; align-items: center; gap: 4px; margin-right: 10px; font-size: .9rem; }
        .leyenda i { width: 12px; height: 12px; border-radius: 3px; display: inline-block; }
        #calendar { background: #fff; padding: 12px; border-radius: 8px; max-width: 1200px; margin: 0 auto; }
        dialog { border: none; border-radius: 8px; padding: 20px; width: min(420px, 90vw); }
        dialog form label { display: block; margin-top: 10px; font-size: .9rem; }
        dialog input, dialog select, dialog textarea { width: 100%; padding: 6px; box-sizing: border-box; }
        .acciones { margin-top: 16px; display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
        button { padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; }
        .primario { background: #3b82f6; color: #fff; }
        .peligro { background: #ef4444; color: #fff; }
        .exito { background: #10b981; color: #fff; }
    </style>
</head>
<body>
    <header>
        <h1>Agenda de Citas Médicas</h1>
        <select id="filtroDoctor"></select>
        <div class="leyenda">
            <span><i style="background:#f59e0b"></i>Pendiente</span>
            <span><i style="background:#3b82f6"></i>Confirmada</span>
            <span><i style="background:#9ca3af"></i>Cancelada</span>
            <span><i style="background:#10b981"></i>Atendida</span>
        </div>
    </header>

    <div id="calendar"></div>

    <dialog id="modalCrear">
        <form id="formCrear">
            <h3>Nueva cita</h3>
            <label>Paciente <select name="paciente_id" required></select></label>
            <label>Doctor <select name="doctor_id" required></select></label>
            <label>Inicio <input type="datetime-local" name="inicio" required></label>
            <label>Fin <input type="datetime-local" name="fin" required></label>
            <label>Motivo <textarea name="motivo" required maxlength="255"></textarea></label>
            <div class="acciones">
                <button type="button" onclick="modalCrear.close()">Cerrar</button>
                <button type="submit" class="primario">Guardar</button>
            </div>
        </form>
    </dialog>

    <dialog id="modalDetalle">
        <h3>Detalle de la cita</h3>
        <div id="detalleContenido"></div>
        <div class="acciones">
            <button type="button" class="primario" data-estado="confirmada">Confirmar</button>
            <button type="button" class="exito" data-estado="atendida">Atender</button>
            <button type="button" class="peligro" data-estado="cancelada">Cancelar cita</button>
            <button type="button" onclick="modalDetalle.close()">Cerrar</button>
        </div>
    </dialog>

    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/calendario.js') }}"></script>
</body>
</html>