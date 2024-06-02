@extends('layouts.app')

@section('content')
    <!-- Calendar Container -->
    <div id="calendar" class="container mt-5"></div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Datos Del Evento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-none">
                        ID:
                        <input type="text" name="txtID" id="txtID">
                        <br/>
                        Fecha:
                        <input type="text" name="txtFecha" id="txtFecha">
                        <br/>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Titulo:</label>
                            <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Hora:</label>
                            <div class="input-group">
                                <select class="form-control" name="hour" id="hour">
                                    <!-- Opciones de hora -->
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select class="form-control" name="minute" id="minute">
                                    <!-- Opciones de minuto -->
                                    @for ($i = 0; $i < 60; $i+=10)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select class="form-control" name="ampm" id="ampm">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                            <input type="hidden" name="txtHora" id="txtHora">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Descripción:</label>
                            <textarea name="txtDescripcion" class="form-control" id="txtDescripcion" cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Color:</label>
                            <input type="color" class="form-control" name="txtColor" id="txtColor">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnAgregar" class="btn btn-success">Agregar</button>
                    <button id="btnModificar" class="btn btn-warning">Modificar</button>
                    <button id="btnBorrar" class="btn btn-danger">Borrar</button>
                    <button id="btnCancelar" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- FullCalendar code -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            background-color: white; /* Fondo de la página blanco */
        }
        #calendar { max-width: 900px; margin: 40px auto; }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap JavaScript (including Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- FullCalendar interaction plugin -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/interaction/main.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today,Miboton',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                customButtons: {
                    Miboton: {
                        text: "Botón",
                        click: function() {
                            $('#exampleModal').modal('toggle');
                        }
                    }
                },
                dateClick: function(info) {
                    limpiarFormulario();
                    $('#txtFecha').val(info.dateStr);

                    $('#btnAgregar').prop("disabled",false);
                    $('#btnModificar').prop("disabled",true);
                    $('#btnBorrar').prop("disabled",true);

                    $('#exampleModal').modal('toggle');
                },
                eventClick: function(info) {
                    $('#btnAgregar').prop("disabled",true);
                    $('#btnModificar').prop("disabled",false);
                    $('#btnBorrar').prop("disabled",false);

                    // Llenar los datos del modal con la información del evento
                    $('#txtID').val(info.event.id);
                    $('#txtFecha').val(info.event.start.toISOString().slice(0, 10));
                    $('#txtTitulo').val(info.event.title);
                    $('#txtHora').val(info.event.start.toISOString().slice(11, 16));
                    $('#txtDescripcion').val(info.event.extendedProps.descripcion);
                    $('#txtColor').val(info.event.backgroundColor);
                    $('#exampleModal').modal('toggle');
                },
                events: "{{ url('/eventos/show') }}"
            });

            calendar.setOption('locale', 'es');
            calendar.render();

            $('#btnAgregar').click(function(){
                var nuevoEvento = recolectarDatosGui("POST");
                EnviarInformacion('', nuevoEvento);
            });

            $('#btnBorrar').click(function(){
                var nuevoEvento = recolectarDatosGui("DELETE");
                EnviarInformacion('/'+$('#txtID').val(), nuevoEvento);
            });

            $('#btnModificar').click(function(){
                var nuevoEvento = recolectarDatosGui("PATCH");
                EnviarInformacion('/'+$('#txtID').val(), nuevoEvento);
            });

            function recolectarDatosGui(method){
                return {
                    id: $('#txtID').val(),
                    title: $('#txtTitulo').val(),
                    descripcion: $('#txtDescripcion').val(),
                    color: $('#txtColor').val(),
                    textColor: $('#txtColor').val(),
                    start: $('#txtFecha').val() + " " + $('#txtHora').val(),
                    end: $('#txtFecha').val() + " " + $('#txtHora').val(),
                    '_token': $("meta[name='csrf-token']").attr("content"),
                    '_method': method
                };
            }

            function EnviarInformacion(accion, objEvento){
                $.ajax({
                    type: "POST",
                    url: "{{ url('/eventos') }}" + accion,
                    data: objEvento,
                    success: function(msg){
                        console.log(msg);
                        calendar.refetchEvents(); // Actualizar el calendario después de agregar el evento
                        $('#exampleModal').modal('hide'); // Ocultar el modal después de agregar el evento
                    },
                    error: function(){
                        alert("Hay un error");
                    }
                });
            }

            function limpiarFormulario() {
                $('#txtID').val("");
                $('#txtFecha').val("");
                $('#txtTitulo').val("");
                $('#txtHora').val("07:00");
                $('#txtDescripcion').val("");
                $('#txtColor').val("");
            }

            // Convertir a formato de 24 horas
            function updateHiddenInput() {
                var hour = document.getElementById('hour').value;
                var minute = document.getElementById('minute').value;
                var ampm = document.getElementById('ampm').value;

                if (ampm === 'PM' && hour !== '12') {
                    hour = (parseInt(hour, 10) + 12).toString().padStart(2, '0');
                } else if (ampm === 'AM' && hour === '12') {
                    hour = '00';
                }

                document.getElementById('txtHora').value = hour + ':' + minute;
            }

            document.getElementById('hour').addEventListener('change', updateHiddenInput);
            document.getElementById('minute').addEventListener('change', updateHiddenInput);
            document.getElementById('ampm').addEventListener('change', updateHiddenInput);
            updateHiddenInput();
        });
    </script>
@endsection
