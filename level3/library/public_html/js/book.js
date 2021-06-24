// Хорошо ли это,что ссылки на удаление, на клики находятся в паблике?

/*------------------ Sending clicks increasing request by clicking on the button ----------------*/
$('.btnBookID').click(function(event) {
    var id = $('#id').attr('book-id');
    $.ajax({
        url: 'http://library/book/click/' + id,
        method: 'get',
        data: {},
        success: function(data){   /* функция которая будет выполнена после успешного запроса.  */
            alert(data);            /* В переменной data содержится ответ от index.php. */
        }
    })
});


// $.ajax({
//  */
// 	url: '/index.php',         /* Куда пойдет запрос */
// method: 'get',             /* Метод передачи (post или get) */
//     dataType: 'html',          /* Тип данных в ответе (xml, json, script, html). */
//     data: {text: 'Текст'},     /* Параметры передаваемые в запросе. */
// success: function(data){   /* функция которая будет выполнена после успешного запроса.  */
//     alert(data);            /* В переменной data содержится ответ от index.php. */
// }
// });

//  $.get('/index.php', {text: 'Текст'}, function(data){
//      alert(data);
//  });


//-------------------------POST------------------------//
/*
$.ajax({
    url: '/index.php',
    method: 'post',
    dataType: 'html',
    data: {text: 'Текст'},
    success: function(data){
        alert(data);
    }
});
*/
/*
$.post('/index.php', {text: 'Текст'}, function(data){
    alert(data);
});
*/
