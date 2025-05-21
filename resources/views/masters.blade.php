@extends('layout.app')
@section('title')
    Наши мастера
@endsection
@section('main')
    <div class="container" id="Masters">
        <div class="min-container">

            <div class="mb-2">
                <input type="text" name="search_master" id="search_master"
                       class="form-settings search-form" placeholder="Поиск" v-model="search_master"
                       :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
            </div>

            <div class="mb-3">
                <div class="master-cards" v-if="masters.length > 0">
                    <div class="master-card" v-for="master in searchMasters">
                        <img :src="`/${master.img}`" alt="" v-if="master.img != ''">
                        <svg v-else viewBox="0 0 304 304" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M214.249 120.277C214.249 152.83 186.16 179.717 150.88 179.717C115.599 179.717 87.5107 152.83 87.5107 120.277C87.5107 87.7231 115.599 60.8359 150.88 60.8359C186.16 60.8359 214.249 87.7231 214.249 120.277Z" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                            <path d="M264.83 294.265C264.83 282.274 262.388 270.399 257.643 259.321C252.898 248.242 245.944 238.176 237.177 229.696C228.41 221.217 218.002 214.491 206.547 209.902C195.093 205.313 182.815 202.951 170.417 202.951" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                            <path d="M39.292 294.265C39.292 282.274 41.8066 270.399 46.6921 259.321C51.5777 248.242 58.7386 238.176 67.766 229.696C76.7933 221.217 87.5104 214.491 99.3052 209.902C111.1 205.313 123.742 202.951 136.508 202.951H170.421" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                            <rect x="5" y="5" width="294" height="294" rx="15" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        </svg>
                        <a href="#" @click="viewMaster(master.id)">
                            <h3 :class="theme === 'light' ? 'p-light':'p-night'">
                                @{{ master.specialization }}
                            </h3>
                            <h3>
                                <span>@{{ master.name }}</span>
                            </h3>
                        </a>
                    </div>
                </div>

                <div v-if="masters.length == 0" :class="theme === 'light' ? 'p-light':'p-night'" >
                    <p style="text-align: center">Пока не добавлено ни одного мастера</p>
                </div>

                <div :class="theme === 'light' ? 'p-light':'p-night'" v-if="searchMasters.length == 0 && masters.length > 0">
                    <p style="text-align: center">Ничего не найдено</p>
                </div>
            </div>
        </div>
    </div>

    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {
                    masters: [],
                    search_master: '',

                    theme: localStorage.getItem('theme') || 'light'

                }
            },

            methods: {

                // get-methods
                async getMasters() {
                    const response = await fetch('{{route('getAllMasters')}}');
                    this.masters = await response.json();
                },

                viewMaster(id) {
                    window.location.href = `/masters/${id}`;
                }

            },

            computed: {
                searchMasters() {
                    return this.search_master != '' ? this.masters.filter(master =>
                        master.name.toLowerCase().includes(this.search_master.toLowerCase()) ||
                        master.specialization.toLowerCase().includes(this.search_master.toLowerCase())) : this.masters;
                },
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

        Vue.createApp(App).mount('#Masters');
    </script>

@endsection
