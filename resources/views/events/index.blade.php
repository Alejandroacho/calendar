@extends('layouts.app')

@section('scripts')
    <link rel="stylesheet" href="{{asset('fullcalendar/core/main.css')}}">
    <link rel="stylesheet" href="{{asset('fullcalendar/daygrid/main.css')}}">
    <link rel="stylesheet" href="{{asset('fullcalendar/list/main.css')}}">
    <link rel="stylesheet" href="{{asset('fullcalendar/timegrid/main.css')}}">

    <script src="{{asset('fullcalendar/core/main.js')}}" defer></script>
    <script src="{{asset('fullcalendar/interaction/main.js')}}" defer></script>
    <script src="{{asset('fullcalendar/daygrid/main.js')}}" defer></script>
    <script src="{{asset('fullcalendar/list/main.js')}}" defer></script>
    <script src="{{asset('fullcalendar/timegrid/main.js')}}" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'dayGrid', 'interaction', 'timeGrid', 'list' ],
                //defaultView:'timeGridWeek'
                header:{
                    left:'prev,next today, Button',
                    center:'title',
                    right:'dayGridMonth, timeGridWeek, timeGridDay'
                },
                customButtons:{
                    Button:{
                        text:"Actividades",
                        click:function(){
                            $('#exampleModal').modal('toggle');
                        }
                    }
                },
                dateClick:function(info){
                    clearForm();
                    $('#date').val(info.dateStr);

                    $("#btnAdd").prop("disabled", false);
                    $("#btnEdit").prop("disabled", true);
                    $("#btnDelete").prop("disabled", true);
                    $("#btnCancel").prop("disabled", false);

                    $('#createModal').modal('toggle');
                    console.log(info.event.id);
                },
                eventClick:function(info){

                    month = (info.event.start.getMonth()+1);
                    month = (month<10)?"0"+month:month;
                    day = (info.event.start.getDate());
                    day = (day<10)?"0"+day:day;
                    year = (info.event.start.getFullYear());
                    date = (year+"-"+month+"-"+day);
                    hour = (info.event.start.getHours());
                    hour = (hour<10)?"0"+hour:hour;
                    minutes = (info.event.start.getMinutes());
                    minutes = (minutes<10)?"0"+minutes:minutes;
                    time = (hour+":"+minutes)


                    hourEnd = (info.event.end.getHours());
                    hourEnd = (hourEnd<10)?"0"+hourEnd:hourEnd;
                    minutesEnd = (info.event.end.getMinutes());
                    minutesEnd = (minutesEnd<10)?"0"+minutesEnd:minutesEnd;
                    timeEnd = (hourEnd+":"+minutesEnd)

                    $('#id').val(info.event.id);   
                    $('#title').val(info.event.title);   
                    $('#date').val(date);
                    $('#start').val(time);
                    $('#end').val(timeEnd);
                    $('#description').val(info.event.extendedProps.description);
                    $('#color').val(info.event.backgroundColor);
                    console.log(info.event.id);


                    $("#btnAdd").prop("disabled", true);
                    $("#btnEdit").prop("disabled", false);
                    $("#btnDelete").prop("disabled", false);
                    $("#btnCancel").prop("disabled", false);


                    $('#createModal').modal('toggle');
                },
                events:"{{ url ('event/show') }}"
            });
            calendar.setOption('locale', 'Es');
            calendar.render();

            $('#btnAdd').click(function(){
                eventObj=recolectData("POST");
                console.log(eventObj);
                sendData('', eventObj);
            });

            $('#btnEdit').click(function(){
                eventObj=recolectData("PATCH");
                sendData('/'+$('#id').val(), eventObj);
            });

            $('#btnDelete').click(function(){
                eventObj=recolectData("DELETE");
                sendData('/'+$('#id').val(), eventObj);
            });

            function recolectData(method){
                newEvent={
                    id:$('#id').val(),
                    title:$('#title').val(),
                    description:$('#description').val(),
                    color:$('#color').val(),
                    textColor:'#FFFFFF',
                    start:$('#date').val()+" "+$('#start').val(),
                    end:$('#date').val()+" "+$('#end').val(),

                    '_token':$("meta[name='csrf-token']").attr("content"),
                    '_method':method
                }
                return(newEvent);
            }

            function sendData(action, eventObj){
                $.ajax({
                    type:"POST",
                    url:"{{url('/event')}}"+action,
                    data:eventObj,
                    success: function(msg){
                        console.log(msg);
                        $('#createModal').modal('toggle');
                        calendar.refetchEvents();
                    },
                    error: function(){
                        alert("Hubo un error");
                    }
                });
            }

            function clearForm(){
                $('#id').val("");   
                $('#title').val("");   
                $('#date').val("");
                $('#start').val("");
                $('#description').val("");
                $('#color').val("");
            }

        });
    </script>

@endsection

@section('content')
    <div class="container">
        <div id="calendar"></div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                Titulo
                <input type="text" name="title" id="title"> <br>
                <input type="hidden" name="date" id="date"> <br>
                Inicio
                <input type="time" min="07:00" max="24:00" step="600" name="start" id="start"> <br>
                Final
                <input type="time" min="07:00" max="24:00" step="600" name="end" id="end"> <br>
                Descripcion
                <textarea name="description" id="description"> </textarea> <br>
                Color
                <input type="color" name="color" id="color"><br>
            </div>
            <div class="modal-footer">
                <button id="btnAdd" type="button" class="btn btn-success">Agregar</button>
                <button id="btnEdit" type="button" class="btn btn-warning">Editar</button>
                <button id="btnDelete" type="button" class="btn btn-danger">Borrar</button>
                <button id="btnCancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </div>

@endsection