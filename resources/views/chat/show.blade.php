@extends('layouts.app')

@push('styles')
<style>
    #users > li {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Real time chat</div>
                <div class="card-body">
                    
                    <div class="row p-2">
                        <!-- chat messages list -->
                        <div class="col-sm-10">

                            <div class="row">
                                <div class="col-sm-12 border rounded-lg p-2">
                                    <ul id="messages" class="list-unstyled overflow-auto" style="height: 45vh;">
                                        <li>test1: hello from test 1</li>
                                        <li>test2: hello from test 2</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <form>
                                <div class="row py-3">
                                    <div class="col-sm-10">
                                        <input id="message" type="text" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <button id="send" class="btn btn-success btn-block">Send</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                         <!-- /chat messages list -->
                         <!-- chat users list -->
                         <div class="col-sm-2 border rounded-lg">
                            <p><strong>Online Now</strong></p>
                            <ul id="users" class="list-unstyled overflow-auto text-info" style="height: 45vh;">
                                
                            </ul>
                         </div>
                         <!-- chat users list -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

    const usersList = document.getElementById('users');
    const messagesList = document.getElementById('messages');
    

    Echo.join('chat')
        .here((users) => {

            users.forEach( (user, index) => {
                let listItem = document.createElement('li');

                listItem.setAttribute('id', user.id);
                listItem.setAttribute('onclick', 'greetUser("'+ user.id + '")');
                listItem.innerText = user.name;

                usersList.appendChild( listItem );
            });
        })
        .joining( (user) => {
            let listItem = document.createElement('li');

            listItem.setAttribute('id', user.id);
            listItem.setAttribute('onclick', 'greetUser("'+ user.id + '")');
            listItem.innerText = user.name;

            usersList.appendChild( listItem );
        })
        .leaving( (user) => {
            const listItem = document.getElementById(user.id);
            listItem.parentNode.removeChild(listItem);
        })
        .listen( 'MessageSend', (e) => {
            let listItem = document.createElement('li');

            //listItem.setAttribute('id', 'message-' + e.user.id);
            listItem.innerText = e.user.name +': ' + e.message;

            messagesList.appendChild( listItem );
        });


</script>

<script>    


    const message = document.getElementById('message');
    const sendBtn = document.getElementById('send');

    sendBtn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('test');

        window.axios.post('/chat/message', {
            message: message.value
        });

        message.value = '';
    });


    
</script>

<script>
    function greetUser ( id ) {
        window.axios.post('/chat/greet/' + id);
    }
</script>


<script>
    
    Echo.private('chat.greet.{{ auth()->user()->id }}')
    .listen('GreetingSent', ( e ) => {
        console.log(e);
        let listItem = document.createElement('li');

        //listItem.setAttribute('id', 'message-' + e.user.id);
        listItem.innerText = e.message;

        listItem.classList.add('text-success');
     
        messagesList.appendChild( listItem );

    });

</script>

@endpush