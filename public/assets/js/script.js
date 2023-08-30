$(function(){
    $('form[name="booking_car"]').submit(function(e){
        e.preventDefault();
        let car_id = $('#booking_car_car').val()
        $.post('/api/v1/bookingcar', { car: car_id }, function(data, status){
            if (data.message) {
                alert(data.message)
            }
            console.log( { data: data, status: status })
        }, 'json').fail(function(e) {
            if ( e.responseJSON.message )
                alert( e.responseJSON.message );
        })
    })
    $('form[name="release_car"]').submit(function(e){
        e.preventDefault();
        let car_id = $('#release_car_car').val()
        $.post('/api/v1/releasecar', { car: car_id }, function(data, status){
            if (data.message) {
                alert(data.message)
            }
            console.log( { data: data, status: status })
        }, 'json').fail(function(e) {
            if ( e.responseJSON.message )
                alert( e.responseJSON.message );
        })
    })
})