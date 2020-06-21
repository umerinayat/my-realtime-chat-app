require('./bootstrap');

const $notification = $('#notification');

Echo.private('notifications')
    .listen('UserSessionChanged', ( e ) => {

        console.log( e );

        $notification.text(e.message);

        $notification.removeClass('invisible');
        $notification.removeClass('alert-success');
        $notification.removeClass('alert-danger');

        $notification.addClass('alert-' + e.type);

    });
