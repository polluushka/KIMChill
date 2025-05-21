@extends('layout.app')
@section('title')
    Подтверждение записи
@endsection
@section('main')
    <div class="container" id="Confirm">
        <div class="min-container">

            <div class="mb-2">
                <div class="title" style="margin-top: 3rem">
                    <h1 class="mb-1">Ваша запись на *дата* в *время*. Вы подтверждаете её?</h1>
                </div>

                <div class="d-flex">
                    <button class="danger-btn" style="padding: 0.5rem 1rem; margin-right: 0.5rem">Отменить</button>
                    <button class="btn-full-form">Подтвердить</button>
                </div>

            </div>
        </div>
    </div>

    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {


                }
            },

            methods: {



            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });
            },

            mounted() {
                this.getMasters();
            }

        }

        Vue.createApp(App).mount('#Confirm');
    </script>

@endsection
