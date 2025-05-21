@extends('layout.app')
@section('title')
    Административная панель
@endsection
@section('main')
    <div class="container" id="Admin">
        <div class="min-container">
            <div class="buttons">
                <button class="btn-empty p-btn-empty" type="button" @click="open_categories">Категории</button>
                <button class="btn-empty p-btn-empty" type="button" @click="open_qualifications">Квалификации</button>
                <button class="btn-empty p-btn-empty" type="button" @click="open_masters">Мастера</button>
                <button class="btn-empty p-btn-empty" type="button" @click="open_admins">Админы</button>
                <button class="btn-empty p-btn-empty" type="button" @click="open_services">Услуги</button>
                <button class="btn-empty p-btn-empty" type="button" @click="open_calendar">Расписание</button>
                <button class="btn-empty p-btn-empty" type="button" @click="open_applications">Записи</button>
            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="categories" v-if="categories_flag === true">
                <div class="mb-1">
                    <input type="text" name="search_category" id="search_category"
                           class="form-settings search-form" placeholder="Поиск" v-model="search_category"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="mb-1">
                    <div class="button-end">
                        <button class="create-btn" type="button" @click="create_category_modal">Добавить</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="list mb-1" v-if="categories.length > 0">
                        <table class="table-list other-table-list">
                            <thead>
                            <tr>
                                <td><span>ID</span></td>
                                <td><span>Категория</span></td>
                                <td><span>Действия</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(category, index) in filteredCategories">
                                <tr v-if="index < 5 || category_open_short === true">
                                    <td style="width:10%">@{{ index + 1 }}</td>
                                    <td style="width:60%">@{{ category.title }}</td>
                                    <td style="width:30%" class="btns-center">
                                        <button class="secondary-empty-btn" type="button"
                                                :class="theme === 'light' ? 'secondary-empty-btn-light':'secondary-empty-btn-night'"
                                                @click="edit_category_modal(category)">Изменить</button>
                                        <button class="danger-btn" @click="delete_category_modal(category.id)" type="button">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.2143 2.49972H15.7857C16.032 2.49972 16.2682 2.59755 16.4423 2.77169C16.6165 2.94583 16.7143 3.18202 16.7143 3.42829V5.28544H9.28571V3.42829C9.28571 3.18202 9.38355 2.94583 9.55769 2.77169C9.73183 2.59755 9.96801 2.49972 10.2143 2.49972ZM18.5714 5.28544V3.42829C18.5714 2.68948 18.2779 1.98092 17.7555 1.45849C17.2331 0.936072 16.5245 0.642578 15.7857 0.642578L10.2143 0.642578C9.47547 0.642578 8.76691 0.936072 8.24449 1.45849C7.72206 1.98092 7.42857 2.68948 7.42857 3.42829V5.28544H0.928571C0.682299 5.28544 0.446113 5.38327 0.271972 5.55741C0.0978313 5.73155 0 5.96773 0 6.21401C0 6.46028 0.0978313 6.69646 0.271972 6.87061C0.446113 7.04475 0.682299 7.14258 0.928571 7.14258H1.92771L3.51186 26.9397C3.58656 27.8705 4.00911 28.739 4.69537 29.3723C5.38162 30.0055 6.28122 30.3571 7.215 30.3569H18.785C19.7188 30.3571 20.6184 30.0055 21.3046 29.3723C21.9909 28.739 22.4134 27.8705 22.4881 26.9397L24.0723 7.14258H25.0714C25.3177 7.14258 25.5539 7.04475 25.728 6.87061C25.9022 6.69646 26 6.46028 26 6.21401C26 5.96773 25.9022 5.73155 25.728 5.55741C25.5539 5.38327 25.3177 5.28544 25.0714 5.28544H18.5714ZM22.2077 7.14258L20.6366 26.7911C20.5992 27.2565 20.3879 27.6908 20.0448 28.0074C19.7017 28.324 19.2519 28.4998 18.785 28.4997H7.215C6.74811 28.4998 6.29831 28.324 5.95518 28.0074C5.61206 27.6908 5.40078 27.2565 5.36343 26.7911L3.79229 7.14258H22.2077ZM8.30329 8.99972C8.54905 8.98552 8.79039 9.06948 8.97427 9.23316C9.15814 9.39683 9.2695 9.62683 9.28386 9.87258L10.2124 25.6583C10.2222 25.9012 10.1363 26.1382 9.97326 26.3185C9.81021 26.4987 9.58296 26.6079 9.34033 26.6225C9.0977 26.637 8.85902 26.5559 8.67554 26.3965C8.49206 26.237 8.37841 26.012 8.359 25.7697L7.42857 9.98401C7.42103 9.86202 7.43765 9.73974 7.47747 9.62418C7.51729 9.50863 7.57954 9.40207 7.66063 9.31063C7.74172 9.21918 7.84007 9.14465 7.95003 9.09129C8.06 9.03794 8.17941 9.00682 8.30143 8.99972H8.30329ZM17.6967 8.99972C17.8187 9.00682 17.9381 9.03794 18.0481 9.09129C18.1581 9.14465 18.2564 9.21918 18.3375 9.31063C18.4186 9.40207 18.4809 9.50863 18.5207 9.62418C18.5605 9.73974 18.5771 9.86202 18.5696 9.98401L17.641 25.7697C17.636 25.8931 17.6065 26.0143 17.5542 26.1261C17.5018 26.238 17.4277 26.3383 17.3361 26.4211C17.2445 26.504 17.1373 26.5677 17.0208 26.6086C16.9042 26.6495 16.7807 26.6668 16.6574 26.6593C16.5342 26.6519 16.4136 26.62 16.3028 26.5654C16.1921 26.5109 16.0933 26.4347 16.0123 26.3415C15.9313 26.2483 15.8697 26.1399 15.8311 26.0225C15.7925 25.9052 15.7777 25.7814 15.7876 25.6583L16.7161 9.87258C16.7305 9.62683 16.8419 9.39683 17.0257 9.23316C17.2096 9.06948 17.451 8.98552 17.6967 8.99972ZM13 8.99972C13.2463 8.99972 13.4825 9.09755 13.6566 9.27169C13.8307 9.44583 13.9286 9.68202 13.9286 9.92829V25.714C13.9286 25.9603 13.8307 26.1965 13.6566 26.3706C13.4825 26.5447 13.2463 26.6426 13 26.6426C12.7537 26.6426 12.5175 26.5447 12.3434 26.3706C12.1693 26.1965 12.0714 25.9603 12.0714 25.714V9.92829C12.0714 9.68202 12.1693 9.44583 12.3434 9.27169C12.5175 9.09755 12.7537 8.99972 13 8.99972Z" fill="white"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>

                    <div class="button-end" v-if="filteredCategories.length > 5">
                        <button class="full-btn" @click="category_pagination"
                                v-if="category_open_short === false && categories.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="category_pagination"
                                v-if="category_open_short === true && categories.length > 5">Свернуть</button>
                    </div>

                    <div v-if="categories.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одной категории</p>
                    </div>

                    <div v-if="filteredCategories.length == 0 && categories.length > 0">
                        <p style="text-align: center">Ничего не найдено</p>
                    </div>
                </div>

            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="qualifications" v-if="qualifications_flag === true">
                <div class="mb-1">
                    <input type="text" name="search_qualification" id="search_qualification"
                           class="form-settings search-form" placeholder="Поиск" v-model="search_qualification"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="mb-1">
                    <div class="button-end">
                        <button class="create-btn" type="button" @click="create_qualification_modal">Добавить</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="list mb-1" v-if="qualifications.length > 0">
                        <table class="table-list other-table-list">
                            <thead>
                            <tr>
                                <td><span>ID</span></td>
                                <td><span>Квалификация</span></td>
                                <td><span>Действия</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(qualification, index) in filteredQualifications">
                                <tr v-if="index < 5 || qualification_open_short === true">
                                    <td style="width:10%">@{{ index + 1 }}</td>
                                    <td style="width:60%">@{{ qualification.title }}</td>
                                    <td style="width:30%" class="btns-center">
                                        <button class="secondary-empty-btn" type="button"
                                                :class="theme === 'light' ? 'secondary-empty-btn-light':'secondary-empty-btn-night'"
                                                @click="edit_qualification_modal(qualification)">Изменить</button>
                                        <button class="danger-btn" @click="delete_qualification_modal(qualification.id)" type="button">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.2143 2.49972H15.7857C16.032 2.49972 16.2682 2.59755 16.4423 2.77169C16.6165 2.94583 16.7143 3.18202 16.7143 3.42829V5.28544H9.28571V3.42829C9.28571 3.18202 9.38355 2.94583 9.55769 2.77169C9.73183 2.59755 9.96801 2.49972 10.2143 2.49972ZM18.5714 5.28544V3.42829C18.5714 2.68948 18.2779 1.98092 17.7555 1.45849C17.2331 0.936072 16.5245 0.642578 15.7857 0.642578L10.2143 0.642578C9.47547 0.642578 8.76691 0.936072 8.24449 1.45849C7.72206 1.98092 7.42857 2.68948 7.42857 3.42829V5.28544H0.928571C0.682299 5.28544 0.446113 5.38327 0.271972 5.55741C0.0978313 5.73155 0 5.96773 0 6.21401C0 6.46028 0.0978313 6.69646 0.271972 6.87061C0.446113 7.04475 0.682299 7.14258 0.928571 7.14258H1.92771L3.51186 26.9397C3.58656 27.8705 4.00911 28.739 4.69537 29.3723C5.38162 30.0055 6.28122 30.3571 7.215 30.3569H18.785C19.7188 30.3571 20.6184 30.0055 21.3046 29.3723C21.9909 28.739 22.4134 27.8705 22.4881 26.9397L24.0723 7.14258H25.0714C25.3177 7.14258 25.5539 7.04475 25.728 6.87061C25.9022 6.69646 26 6.46028 26 6.21401C26 5.96773 25.9022 5.73155 25.728 5.55741C25.5539 5.38327 25.3177 5.28544 25.0714 5.28544H18.5714ZM22.2077 7.14258L20.6366 26.7911C20.5992 27.2565 20.3879 27.6908 20.0448 28.0074C19.7017 28.324 19.2519 28.4998 18.785 28.4997H7.215C6.74811 28.4998 6.29831 28.324 5.95518 28.0074C5.61206 27.6908 5.40078 27.2565 5.36343 26.7911L3.79229 7.14258H22.2077ZM8.30329 8.99972C8.54905 8.98552 8.79039 9.06948 8.97427 9.23316C9.15814 9.39683 9.2695 9.62683 9.28386 9.87258L10.2124 25.6583C10.2222 25.9012 10.1363 26.1382 9.97326 26.3185C9.81021 26.4987 9.58296 26.6079 9.34033 26.6225C9.0977 26.637 8.85902 26.5559 8.67554 26.3965C8.49206 26.237 8.37841 26.012 8.359 25.7697L7.42857 9.98401C7.42103 9.86202 7.43765 9.73974 7.47747 9.62418C7.51729 9.50863 7.57954 9.40207 7.66063 9.31063C7.74172 9.21918 7.84007 9.14465 7.95003 9.09129C8.06 9.03794 8.17941 9.00682 8.30143 8.99972H8.30329ZM17.6967 8.99972C17.8187 9.00682 17.9381 9.03794 18.0481 9.09129C18.1581 9.14465 18.2564 9.21918 18.3375 9.31063C18.4186 9.40207 18.4809 9.50863 18.5207 9.62418C18.5605 9.73974 18.5771 9.86202 18.5696 9.98401L17.641 25.7697C17.636 25.8931 17.6065 26.0143 17.5542 26.1261C17.5018 26.238 17.4277 26.3383 17.3361 26.4211C17.2445 26.504 17.1373 26.5677 17.0208 26.6086C16.9042 26.6495 16.7807 26.6668 16.6574 26.6593C16.5342 26.6519 16.4136 26.62 16.3028 26.5654C16.1921 26.5109 16.0933 26.4347 16.0123 26.3415C15.9313 26.2483 15.8697 26.1399 15.8311 26.0225C15.7925 25.9052 15.7777 25.7814 15.7876 25.6583L16.7161 9.87258C16.7305 9.62683 16.8419 9.39683 17.0257 9.23316C17.2096 9.06948 17.451 8.98552 17.6967 8.99972ZM13 8.99972C13.2463 8.99972 13.4825 9.09755 13.6566 9.27169C13.8307 9.44583 13.9286 9.68202 13.9286 9.92829V25.714C13.9286 25.9603 13.8307 26.1965 13.6566 26.3706C13.4825 26.5447 13.2463 26.6426 13 26.6426C12.7537 26.6426 12.5175 26.5447 12.3434 26.3706C12.1693 26.1965 12.0714 25.9603 12.0714 25.714V9.92829C12.0714 9.68202 12.1693 9.44583 12.3434 9.27169C12.5175 9.09755 12.7537 8.99972 13 8.99972Z" fill="white"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>

                    <div class="button-end" v-if="filteredQualifications.length > 5">
                        <button class="full-btn" @click="qualification_pagination"
                                v-if="qualification_open_short === false && qualifications.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="qualification_pagination"
                                v-if="qualification_open_short === true && qualifications.length > 5">Свернуть</button>
                    </div>


                    <div v-if="qualifications.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одной квалификации</p>
                    </div>

                    <div v-if="filteredQualifications.length == 0 && qualifications.length > 0">
                        <p style="text-align: center">Ничего не найдено</p>
                    </div>

                </div>

            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="masters" v-if="masters_flag === true">
                <div class="mb-1">
                    <input type="text" name="search_master" id="search_master"
                           class="form-settings search-form" placeholder="Поиск" v-model="search_master"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="mb-1 space-between">
                    <div style="width: 30%">
                        <select v-model="master_filter_id" name="filter_master" id="filter_master"
                                class="form-settings select-form"
                                :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }">
                            <option value="0">Все</option>
                            <option v-for="qualification in qualifications" :value="qualification.id">
                                @{{ qualification.title }}</option>
                        </select>
                    </div>
                    <button class="create-btn" type="button" @click="create_master_modal">Добавить</button>
                </div>

                <div class="mb-3">
                    <div class="list mb-1" v-if="masters.length > 0">
                        <table class="table-list">
                            <thead>
                            <tr>
                                <td><span>ID</span></td>
                                <td><span>Имя</span></td>
                                <td><span>Специализация</span></td>
                                <td><span>Квалификация</span></td>
                                <td><span>Действия</span></td>
                            </tr>
                            </thead>
                            <tbody>

                            <template v-for="(master, index) in filteredMasters">
                                <tr v-if="index < 5 || master_open_short === true">
                                    <td style="width:5%">@{{ index + 1 }}</td>
                                    <td style="width:15%">@{{ master.name }}</td>
                                    <td style="width:50%">@{{ master.specialization }}</td>
                                    <td style="width:25%">@{{ master.qualification.title }}</td>
                                    <td style="width:5%" class="btns-center">
                                        <button class="secondary-empty-btn" type="button"
                                                :class="theme === 'light' ? 'secondary-empty-btn-light':'secondary-empty-btn-night'"
                                                @click="edit_master_modal(master)">Изменить</button>
                                        <button class="danger-btn" @click="delete_master_modal(master.id)" type="button">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.2143 2.49972H15.7857C16.032 2.49972 16.2682 2.59755 16.4423 2.77169C16.6165 2.94583 16.7143 3.18202 16.7143 3.42829V5.28544H9.28571V3.42829C9.28571 3.18202 9.38355 2.94583 9.55769 2.77169C9.73183 2.59755 9.96801 2.49972 10.2143 2.49972ZM18.5714 5.28544V3.42829C18.5714 2.68948 18.2779 1.98092 17.7555 1.45849C17.2331 0.936072 16.5245 0.642578 15.7857 0.642578L10.2143 0.642578C9.47547 0.642578 8.76691 0.936072 8.24449 1.45849C7.72206 1.98092 7.42857 2.68948 7.42857 3.42829V5.28544H0.928571C0.682299 5.28544 0.446113 5.38327 0.271972 5.55741C0.0978313 5.73155 0 5.96773 0 6.21401C0 6.46028 0.0978313 6.69646 0.271972 6.87061C0.446113 7.04475 0.682299 7.14258 0.928571 7.14258H1.92771L3.51186 26.9397C3.58656 27.8705 4.00911 28.739 4.69537 29.3723C5.38162 30.0055 6.28122 30.3571 7.215 30.3569H18.785C19.7188 30.3571 20.6184 30.0055 21.3046 29.3723C21.9909 28.739 22.4134 27.8705 22.4881 26.9397L24.0723 7.14258H25.0714C25.3177 7.14258 25.5539 7.04475 25.728 6.87061C25.9022 6.69646 26 6.46028 26 6.21401C26 5.96773 25.9022 5.73155 25.728 5.55741C25.5539 5.38327 25.3177 5.28544 25.0714 5.28544H18.5714ZM22.2077 7.14258L20.6366 26.7911C20.5992 27.2565 20.3879 27.6908 20.0448 28.0074C19.7017 28.324 19.2519 28.4998 18.785 28.4997H7.215C6.74811 28.4998 6.29831 28.324 5.95518 28.0074C5.61206 27.6908 5.40078 27.2565 5.36343 26.7911L3.79229 7.14258H22.2077ZM8.30329 8.99972C8.54905 8.98552 8.79039 9.06948 8.97427 9.23316C9.15814 9.39683 9.2695 9.62683 9.28386 9.87258L10.2124 25.6583C10.2222 25.9012 10.1363 26.1382 9.97326 26.3185C9.81021 26.4987 9.58296 26.6079 9.34033 26.6225C9.0977 26.637 8.85902 26.5559 8.67554 26.3965C8.49206 26.237 8.37841 26.012 8.359 25.7697L7.42857 9.98401C7.42103 9.86202 7.43765 9.73974 7.47747 9.62418C7.51729 9.50863 7.57954 9.40207 7.66063 9.31063C7.74172 9.21918 7.84007 9.14465 7.95003 9.09129C8.06 9.03794 8.17941 9.00682 8.30143 8.99972H8.30329ZM17.6967 8.99972C17.8187 9.00682 17.9381 9.03794 18.0481 9.09129C18.1581 9.14465 18.2564 9.21918 18.3375 9.31063C18.4186 9.40207 18.4809 9.50863 18.5207 9.62418C18.5605 9.73974 18.5771 9.86202 18.5696 9.98401L17.641 25.7697C17.636 25.8931 17.6065 26.0143 17.5542 26.1261C17.5018 26.238 17.4277 26.3383 17.3361 26.4211C17.2445 26.504 17.1373 26.5677 17.0208 26.6086C16.9042 26.6495 16.7807 26.6668 16.6574 26.6593C16.5342 26.6519 16.4136 26.62 16.3028 26.5654C16.1921 26.5109 16.0933 26.4347 16.0123 26.3415C15.9313 26.2483 15.8697 26.1399 15.8311 26.0225C15.7925 25.9052 15.7777 25.7814 15.7876 25.6583L16.7161 9.87258C16.7305 9.62683 16.8419 9.39683 17.0257 9.23316C17.2096 9.06948 17.451 8.98552 17.6967 8.99972ZM13 8.99972C13.2463 8.99972 13.4825 9.09755 13.6566 9.27169C13.8307 9.44583 13.9286 9.68202 13.9286 9.92829V25.714C13.9286 25.9603 13.8307 26.1965 13.6566 26.3706C13.4825 26.5447 13.2463 26.6426 13 26.6426C12.7537 26.6426 12.5175 26.5447 12.3434 26.3706C12.1693 26.1965 12.0714 25.9603 12.0714 25.714V9.92829C12.0714 9.68202 12.1693 9.44583 12.3434 9.27169C12.5175 9.09755 12.7537 8.99972 13 8.99972Z" fill="white"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>
                    <div class="button-end" v-if="filteredMasters.length > 5">
                        <button class="full-btn" @click="master_pagination"
                                v-if="master_open_short === false && masters.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="master_pagination"
                                v-if="master_open_short === true && masters.length > 5">Свернуть</button>
                    </div>

                    <div v-if="masters.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одного мастера</p>
                    </div>

                    <div v-if="filteredMasters.length == 0 && masters.length > 0">
                        <p style="text-align: center">Ничего не найдено</p>
                    </div>

                </div>

            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="admins" v-if="admins_flag === true">
                <div class="mb-1">
                    <input type="text" name="search_admin" id="search_admin"
                           class="form-settings search-form" placeholder="Поиск" v-model="search_admin"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="mb-1">
                    <div class="button-end">
                        <button class="create-btn" type="button" @click="create_admin_modal">Добавить</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="list mb-1" v-if="admins.length > 0">
                        <table class="table-list other-table-list">
                            <thead>
                            <tr>
                                <td><span>ID</span></td>
                                <td><span>Имя и фамилия</span></td>
                                <td><span>Действия</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(admin, index) in filteredAdmins">
                                <tr v-if="index < 5 || admin_open_short === true">
                                    <td style="width:10%">@{{ index + 1 }}</td>
                                    <td style="width:60%">@{{ admin.name }} @{{ admin.surname }}</td>
                                    <td style="width:30%" class="btns-center" v-if="admins.length > 1">
                                        <button class="danger-btn" @click="delete_admin_modal(admin.id)" type="button">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.2143 2.49972H15.7857C16.032 2.49972 16.2682 2.59755 16.4423 2.77169C16.6165 2.94583 16.7143 3.18202 16.7143 3.42829V5.28544H9.28571V3.42829C9.28571 3.18202 9.38355 2.94583 9.55769 2.77169C9.73183 2.59755 9.96801 2.49972 10.2143 2.49972ZM18.5714 5.28544V3.42829C18.5714 2.68948 18.2779 1.98092 17.7555 1.45849C17.2331 0.936072 16.5245 0.642578 15.7857 0.642578L10.2143 0.642578C9.47547 0.642578 8.76691 0.936072 8.24449 1.45849C7.72206 1.98092 7.42857 2.68948 7.42857 3.42829V5.28544H0.928571C0.682299 5.28544 0.446113 5.38327 0.271972 5.55741C0.0978313 5.73155 0 5.96773 0 6.21401C0 6.46028 0.0978313 6.69646 0.271972 6.87061C0.446113 7.04475 0.682299 7.14258 0.928571 7.14258H1.92771L3.51186 26.9397C3.58656 27.8705 4.00911 28.739 4.69537 29.3723C5.38162 30.0055 6.28122 30.3571 7.215 30.3569H18.785C19.7188 30.3571 20.6184 30.0055 21.3046 29.3723C21.9909 28.739 22.4134 27.8705 22.4881 26.9397L24.0723 7.14258H25.0714C25.3177 7.14258 25.5539 7.04475 25.728 6.87061C25.9022 6.69646 26 6.46028 26 6.21401C26 5.96773 25.9022 5.73155 25.728 5.55741C25.5539 5.38327 25.3177 5.28544 25.0714 5.28544H18.5714ZM22.2077 7.14258L20.6366 26.7911C20.5992 27.2565 20.3879 27.6908 20.0448 28.0074C19.7017 28.324 19.2519 28.4998 18.785 28.4997H7.215C6.74811 28.4998 6.29831 28.324 5.95518 28.0074C5.61206 27.6908 5.40078 27.2565 5.36343 26.7911L3.79229 7.14258H22.2077ZM8.30329 8.99972C8.54905 8.98552 8.79039 9.06948 8.97427 9.23316C9.15814 9.39683 9.2695 9.62683 9.28386 9.87258L10.2124 25.6583C10.2222 25.9012 10.1363 26.1382 9.97326 26.3185C9.81021 26.4987 9.58296 26.6079 9.34033 26.6225C9.0977 26.637 8.85902 26.5559 8.67554 26.3965C8.49206 26.237 8.37841 26.012 8.359 25.7697L7.42857 9.98401C7.42103 9.86202 7.43765 9.73974 7.47747 9.62418C7.51729 9.50863 7.57954 9.40207 7.66063 9.31063C7.74172 9.21918 7.84007 9.14465 7.95003 9.09129C8.06 9.03794 8.17941 9.00682 8.30143 8.99972H8.30329ZM17.6967 8.99972C17.8187 9.00682 17.9381 9.03794 18.0481 9.09129C18.1581 9.14465 18.2564 9.21918 18.3375 9.31063C18.4186 9.40207 18.4809 9.50863 18.5207 9.62418C18.5605 9.73974 18.5771 9.86202 18.5696 9.98401L17.641 25.7697C17.636 25.8931 17.6065 26.0143 17.5542 26.1261C17.5018 26.238 17.4277 26.3383 17.3361 26.4211C17.2445 26.504 17.1373 26.5677 17.0208 26.6086C16.9042 26.6495 16.7807 26.6668 16.6574 26.6593C16.5342 26.6519 16.4136 26.62 16.3028 26.5654C16.1921 26.5109 16.0933 26.4347 16.0123 26.3415C15.9313 26.2483 15.8697 26.1399 15.8311 26.0225C15.7925 25.9052 15.7777 25.7814 15.7876 25.6583L16.7161 9.87258C16.7305 9.62683 16.8419 9.39683 17.0257 9.23316C17.2096 9.06948 17.451 8.98552 17.6967 8.99972ZM13 8.99972C13.2463 8.99972 13.4825 9.09755 13.6566 9.27169C13.8307 9.44583 13.9286 9.68202 13.9286 9.92829V25.714C13.9286 25.9603 13.8307 26.1965 13.6566 26.3706C13.4825 26.5447 13.2463 26.6426 13 26.6426C12.7537 26.6426 12.5175 26.5447 12.3434 26.3706C12.1693 26.1965 12.0714 25.9603 12.0714 25.714V9.92829C12.0714 9.68202 12.1693 9.44583 12.3434 9.27169C12.5175 9.09755 12.7537 8.99972 13 8.99972Z" fill="white"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>
                    <div class="button-end" v-if="filteredAdmins.length > 5">
                        <button class="full-btn" @click="admin_pagination"
                                v-if="admin_open_short === false && admins.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="admin_pagination"
                                v-if="admin_open_short === true && admins.length > 5">Свернуть</button>
                    </div>

                    <div v-if="admins.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одного администратора</p>
                    </div>

                    <div v-if="filteredAdmins.length == 0 && admins.length > 0">
                        <p style="text-align: center">Ничего не найдено</p>
                    </div>
                </div>
            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="services" v-if="services_flag === true">
                <div class="mb-1">
                    <input type="text" name="search_service" id="search_service"
                           class="form-settings search-form" placeholder="Поиск" v-model="search_service"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="mb-1 space-between">
                    <div style="width: 30%">
                        <select v-model="service_filter_id" name="filter_service" id="filter_service"
                                class="form-settings select-form"
                                :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }">
                            <option value="0">Все</option>
                            <option v-for="category in categories" :value="category.id">
                                @{{ category.title }}</option>
                        </select>
                    </div>
                    <button class="create-btn" type="button" @click="create_service_modal">Добавить</button>
                </div>

                <div class="mb-3">
                    <div class="list mb-1" v-if="services.length > 0">
                        <table class="table-list">
                            <thead>
                            <tr>
                                <td><span>ID</span></td>
                                <td><span>Процедура</span></td>
                                <td><span>Категория</span></td>
                                <td><span>Действия</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(service, index) in filteredServices">
                                <tr v-if="index < 5 || service_open_short === true">
                                    <td style="width:5%">@{{ index + 1 }}</td>
                                    <td style="width:65%">@{{ service.title }}</td>
                                    <td style="width:20%">@{{ service.category.title }}</td>
                                    <td style="width:10%" class="btns-center">
                                        <button class="secondary-empty-btn" type="button"
                                                :class="theme === 'light' ? 'secondary-empty-btn-light':'secondary-empty-btn-night'"
                                                @click="edit_service_modal(service)">Изменить</button>
                                        <button class="danger-btn" @click="delete_service_modal(service.id)" type="button">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.2143 2.49972H15.7857C16.032 2.49972 16.2682 2.59755 16.4423 2.77169C16.6165 2.94583 16.7143 3.18202 16.7143 3.42829V5.28544H9.28571V3.42829C9.28571 3.18202 9.38355 2.94583 9.55769 2.77169C9.73183 2.59755 9.96801 2.49972 10.2143 2.49972ZM18.5714 5.28544V3.42829C18.5714 2.68948 18.2779 1.98092 17.7555 1.45849C17.2331 0.936072 16.5245 0.642578 15.7857 0.642578L10.2143 0.642578C9.47547 0.642578 8.76691 0.936072 8.24449 1.45849C7.72206 1.98092 7.42857 2.68948 7.42857 3.42829V5.28544H0.928571C0.682299 5.28544 0.446113 5.38327 0.271972 5.55741C0.0978313 5.73155 0 5.96773 0 6.21401C0 6.46028 0.0978313 6.69646 0.271972 6.87061C0.446113 7.04475 0.682299 7.14258 0.928571 7.14258H1.92771L3.51186 26.9397C3.58656 27.8705 4.00911 28.739 4.69537 29.3723C5.38162 30.0055 6.28122 30.3571 7.215 30.3569H18.785C19.7188 30.3571 20.6184 30.0055 21.3046 29.3723C21.9909 28.739 22.4134 27.8705 22.4881 26.9397L24.0723 7.14258H25.0714C25.3177 7.14258 25.5539 7.04475 25.728 6.87061C25.9022 6.69646 26 6.46028 26 6.21401C26 5.96773 25.9022 5.73155 25.728 5.55741C25.5539 5.38327 25.3177 5.28544 25.0714 5.28544H18.5714ZM22.2077 7.14258L20.6366 26.7911C20.5992 27.2565 20.3879 27.6908 20.0448 28.0074C19.7017 28.324 19.2519 28.4998 18.785 28.4997H7.215C6.74811 28.4998 6.29831 28.324 5.95518 28.0074C5.61206 27.6908 5.40078 27.2565 5.36343 26.7911L3.79229 7.14258H22.2077ZM8.30329 8.99972C8.54905 8.98552 8.79039 9.06948 8.97427 9.23316C9.15814 9.39683 9.2695 9.62683 9.28386 9.87258L10.2124 25.6583C10.2222 25.9012 10.1363 26.1382 9.97326 26.3185C9.81021 26.4987 9.58296 26.6079 9.34033 26.6225C9.0977 26.637 8.85902 26.5559 8.67554 26.3965C8.49206 26.237 8.37841 26.012 8.359 25.7697L7.42857 9.98401C7.42103 9.86202 7.43765 9.73974 7.47747 9.62418C7.51729 9.50863 7.57954 9.40207 7.66063 9.31063C7.74172 9.21918 7.84007 9.14465 7.95003 9.09129C8.06 9.03794 8.17941 9.00682 8.30143 8.99972H8.30329ZM17.6967 8.99972C17.8187 9.00682 17.9381 9.03794 18.0481 9.09129C18.1581 9.14465 18.2564 9.21918 18.3375 9.31063C18.4186 9.40207 18.4809 9.50863 18.5207 9.62418C18.5605 9.73974 18.5771 9.86202 18.5696 9.98401L17.641 25.7697C17.636 25.8931 17.6065 26.0143 17.5542 26.1261C17.5018 26.238 17.4277 26.3383 17.3361 26.4211C17.2445 26.504 17.1373 26.5677 17.0208 26.6086C16.9042 26.6495 16.7807 26.6668 16.6574 26.6593C16.5342 26.6519 16.4136 26.62 16.3028 26.5654C16.1921 26.5109 16.0933 26.4347 16.0123 26.3415C15.9313 26.2483 15.8697 26.1399 15.8311 26.0225C15.7925 25.9052 15.7777 25.7814 15.7876 25.6583L16.7161 9.87258C16.7305 9.62683 16.8419 9.39683 17.0257 9.23316C17.2096 9.06948 17.451 8.98552 17.6967 8.99972ZM13 8.99972C13.2463 8.99972 13.4825 9.09755 13.6566 9.27169C13.8307 9.44583 13.9286 9.68202 13.9286 9.92829V25.714C13.9286 25.9603 13.8307 26.1965 13.6566 26.3706C13.4825 26.5447 13.2463 26.6426 13 26.6426C12.7537 26.6426 12.5175 26.5447 12.3434 26.3706C12.1693 26.1965 12.0714 25.9603 12.0714 25.714V9.92829C12.0714 9.68202 12.1693 9.44583 12.3434 9.27169C12.5175 9.09755 12.7537 8.99972 13 8.99972Z" fill="white"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="button-end" v-if="filteredServices.length > 5">
                        <button class="full-btn" @click="service_pagination"
                                v-if="service_open_short === false && services.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="service_pagination"
                                v-if="service_open_short === true && services.length > 5">Свернуть</button>
                    </div>

                    <div v-if="services.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одной услуги</p>
                    </div>

                    <div v-if="filteredServices.length == 0 && services.length > 0">
                        <p style="text-align: center">Ничего не найдено</p>
                    </div>
                </div>

            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="calendar" v-if="calendar_flag === true">
                <div class="mb-1">
                    <input type="text" name="search_calendar" id="search_calendar"
                           class="form-settings search-form" placeholder="Поиск" v-model="search_calendar"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="mb-1 space-between">

                    <select style="width: 30%" name="calendar_filter" id="calendar_filter"
                            class="form-settings select-form" v-model="calendar_filter_id"
                            :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }">
                        <option value="0" selected>Все</option>
                        <option v-for="month in months" :value="month.name_month">
                            @{{ month.name_month }}, @{{ month.year }}</option>
                    </select>

                    <button class="create-btn" type="button" @click="create_calendar_modal">Добавить</button>
                </div>

                <div class="mb-3">
                    <div class="list mb-1" v-if="calendars.length > 0">
                        <table class="table-list other-table-list">
                            <thead>
                            <tr>
                                <td><span>ID</span></td>
                                <td><span>Мастер</span></td>
                                <td><span>Месяц</span></td>
                                <td><span>Год</span></td>
                                <td><span>Действия</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(calendar, index) in filteredCalendars">
                                <tr v-if="index < 5 || calendar_open_short === true">
                                    <td style="width:10%">@{{ index + 1 }}</td>
                                    <td style="width:calc(90% / 4)">@{{ calendar.master_name }}</td>
                                    <td style="width:calc(90% / 4)">@{{ calendar.month_name }}</td>
                                    <td style="width:calc(90% / 4)">@{{ calendar.year }}</td>
                                    <td style="width:calc(90% / 4)" class="btns-center">
                                        <button class="danger-btn" @click="delete_calendar_modal(calendar.id)" type="button">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.2143 2.49972H15.7857C16.032 2.49972 16.2682 2.59755 16.4423 2.77169C16.6165 2.94583 16.7143 3.18202 16.7143 3.42829V5.28544H9.28571V3.42829C9.28571 3.18202 9.38355 2.94583 9.55769 2.77169C9.73183 2.59755 9.96801 2.49972 10.2143 2.49972ZM18.5714 5.28544V3.42829C18.5714 2.68948 18.2779 1.98092 17.7555 1.45849C17.2331 0.936072 16.5245 0.642578 15.7857 0.642578L10.2143 0.642578C9.47547 0.642578 8.76691 0.936072 8.24449 1.45849C7.72206 1.98092 7.42857 2.68948 7.42857 3.42829V5.28544H0.928571C0.682299 5.28544 0.446113 5.38327 0.271972 5.55741C0.0978313 5.73155 0 5.96773 0 6.21401C0 6.46028 0.0978313 6.69646 0.271972 6.87061C0.446113 7.04475 0.682299 7.14258 0.928571 7.14258H1.92771L3.51186 26.9397C3.58656 27.8705 4.00911 28.739 4.69537 29.3723C5.38162 30.0055 6.28122 30.3571 7.215 30.3569H18.785C19.7188 30.3571 20.6184 30.0055 21.3046 29.3723C21.9909 28.739 22.4134 27.8705 22.4881 26.9397L24.0723 7.14258H25.0714C25.3177 7.14258 25.5539 7.04475 25.728 6.87061C25.9022 6.69646 26 6.46028 26 6.21401C26 5.96773 25.9022 5.73155 25.728 5.55741C25.5539 5.38327 25.3177 5.28544 25.0714 5.28544H18.5714ZM22.2077 7.14258L20.6366 26.7911C20.5992 27.2565 20.3879 27.6908 20.0448 28.0074C19.7017 28.324 19.2519 28.4998 18.785 28.4997H7.215C6.74811 28.4998 6.29831 28.324 5.95518 28.0074C5.61206 27.6908 5.40078 27.2565 5.36343 26.7911L3.79229 7.14258H22.2077ZM8.30329 8.99972C8.54905 8.98552 8.79039 9.06948 8.97427 9.23316C9.15814 9.39683 9.2695 9.62683 9.28386 9.87258L10.2124 25.6583C10.2222 25.9012 10.1363 26.1382 9.97326 26.3185C9.81021 26.4987 9.58296 26.6079 9.34033 26.6225C9.0977 26.637 8.85902 26.5559 8.67554 26.3965C8.49206 26.237 8.37841 26.012 8.359 25.7697L7.42857 9.98401C7.42103 9.86202 7.43765 9.73974 7.47747 9.62418C7.51729 9.50863 7.57954 9.40207 7.66063 9.31063C7.74172 9.21918 7.84007 9.14465 7.95003 9.09129C8.06 9.03794 8.17941 9.00682 8.30143 8.99972H8.30329ZM17.6967 8.99972C17.8187 9.00682 17.9381 9.03794 18.0481 9.09129C18.1581 9.14465 18.2564 9.21918 18.3375 9.31063C18.4186 9.40207 18.4809 9.50863 18.5207 9.62418C18.5605 9.73974 18.5771 9.86202 18.5696 9.98401L17.641 25.7697C17.636 25.8931 17.6065 26.0143 17.5542 26.1261C17.5018 26.238 17.4277 26.3383 17.3361 26.4211C17.2445 26.504 17.1373 26.5677 17.0208 26.6086C16.9042 26.6495 16.7807 26.6668 16.6574 26.6593C16.5342 26.6519 16.4136 26.62 16.3028 26.5654C16.1921 26.5109 16.0933 26.4347 16.0123 26.3415C15.9313 26.2483 15.8697 26.1399 15.8311 26.0225C15.7925 25.9052 15.7777 25.7814 15.7876 25.6583L16.7161 9.87258C16.7305 9.62683 16.8419 9.39683 17.0257 9.23316C17.2096 9.06948 17.451 8.98552 17.6967 8.99972ZM13 8.99972C13.2463 8.99972 13.4825 9.09755 13.6566 9.27169C13.8307 9.44583 13.9286 9.68202 13.9286 9.92829V25.714C13.9286 25.9603 13.8307 26.1965 13.6566 26.3706C13.4825 26.5447 13.2463 26.6426 13 26.6426C12.7537 26.6426 12.5175 26.5447 12.3434 26.3706C12.1693 26.1965 12.0714 25.9603 12.0714 25.714V9.92829C12.0714 9.68202 12.1693 9.44583 12.3434 9.27169C12.5175 9.09755 12.7537 8.99972 13 8.99972Z" fill="white"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="button-end" v-if="filteredCalendars.length > 5">
                        <button class="full-btn" @click="calendar_pagination"
                                v-if="calendar_open_short === false && calendars.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="calendar_pagination"
                                v-if="calendar_open_short === true && calendars.length > 5">Свернуть</button>
                    </div>

                    <div  v-if="calendars.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одного расписания</p>
                    </div>

                    <div  v-if="filteredCalendars.length == 0 && calendars.length > 0">
                        <p style="text-align: center">Ничего не найдено</p>
                    </div>
                </div>
            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" id="applications" v-if="applications_flag === true">

                <div class="mb-1" style="width: 35%">
                    <select v-model="applications_status_filter_id" name="status_filter" id="status_filter"
                            class="form-settings select-form"
                            :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }">
                        <option value="0">Все</option>
                        <option value="Запланировано">Запланировано</option>
                        <option value="Проведено">Проведено</option>
                        <option value="Отменено">Отменено</option>
                    </select>
                </div>

                <div :class="message_edit_application ? 'alert-success':''">
                    @{{ message_edit_application }}
                </div>

                <div class="mb-1 applications-container" v-if="applications.length > 0">
                    <div v-for="(application, index) in filterApplications">
                        <div class="application-card" v-if="index < 3 || application_open_short === true">
                            <div class="title">
                                <h4>@{{ application.service.title }}</h4>
                            </div>
                            <div class="space-between table-application">
                                <table class="right-application" style="width: 50%">
                                    <tr class="space-between" style="width: 100%; margin-bottom: 0.5rem">
                                        <td><p><span>Дата:</span></p></td>
                                        <td><p>@{{ date_format(application.date) }}</p></td>
                                    </tr>

                                    <tr style="margin-bottom: 0.5rem" class="space-between">
                                        <td><p><span>Время:</span></p></td>
                                        <td><p>@{{ application.time }}</p></td>
                                    </tr>

                                    <tr style="margin-bottom: 0.5rem" class="space-between">
                                        <td><p><span>Мастер:</span></p></td>
                                        <td><p>@{{ application.master.name }}</p></td>
                                    </tr>

                                    <tr class="space-between">
                                        <td><p><span>Телефон:</span></p></td>
                                        <td><p>@{{ application.tel }}</p></td>
                                    </tr>
                                </table>
                                <table class="left-application" style="width: 50%">

                                    <tr style="margin-bottom: 0.5rem" class="space-between" style="width: 100%">
                                        <td><p><span>Стоимость:</span></p></td>
                                        <td><p>@{{ application.price }} руб.</p></td>
                                    </tr>

                                    <tr style="margin-bottom: 2rem" class="space-between">
                                        <td><p><span>Скидка:</span></p></td>
                                        <td v-if="application.discount"><p>@{{ application.discount }}%</p></td>
                                        <td v-else><p>0%</p></td>
                                    </tr>


                                    <tr class="space-between">
                                        <td><p><span>ИТОГО:</span></p></td>
                                        <td><p>@{{ application.discounted_price }} руб.</p></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mb-03 button-end">
                                <button v-if="application.status === 'Запланировано'"
                                        class="btn-full-form" @click="edit_application_modal(application)">Изменить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-end mb-3" v-if="filterApplications.length > 3">
                    <button class="full-btn" @click="application_pagination"
                            v-if="application_open_short === false && applications.length > 3">ЕЩЁ...</button>
                    <button class="full-btn" @click="application_pagination"
                            v-if="application_open_short === true && applications.length > 3">Свернуть</button>
                </div>

                <div style="margin-bottom: 3rem" v-if="applications.length == 0">
                    <p style="text-align: center">Записи отсутствуют</p>
                </div>

                <div style="margin-bottom: 3rem" v-if="filterApplications.length === 0 && applications.length > 0">
                    <p style="text-align: center">Записи не найдены</p>
                </div>

            </div>
        </div>

        {{--        save-category--}}
        <div class="modal-container" v-if="categories_flag === true" id="createCategoryModal">
            <div class="modal-inside">
                <form id="StoreCategoryForm" @submit.prevent="storeCategory">
                    <div class="title">
                        <h1>Создание категории</h1>
                    </div>

                    <div :class="message_create_category ? 'alert-success':''">
                        @{{ message_create_category }}
                    </div>

                    <div class="mb-1">
                        <input type="text" name="title" id="title" class="form-settings" placeholder="Название" :class="errors_category.title ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_category.title">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="create_category_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        edit-category--}}
        <div class="modal-container" v-if="categories_flag === true" id="editCategoryModal">
            <div class="modal-inside">
                <form id="EditCategoryForm" @submit.prevent="editCategory">
                    <div class="title">
                        <h1>Изменение категории</h1>
                    </div>

                    <div :class="message_edit_category ? 'alert-success':''">
                        @{{ message_edit_category }}
                    </div>

                    <div class="mb-1">
                        <input type="text" name="title_edit" id="title_edit" v-model="category_obj_edit.title" class="form-settings"
                               placeholder="Название" :class="errors_category.title_edit ? 'is-invalid':''">
                        <div class="invalid-feedback" v-for="error in errors_category.title_edit">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="edit_category_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-category--}}
        <div class="modal-container" v-if="categories_flag === true" id="deleteCategoryModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите удалить эту категорию? Это действие невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_category_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteCategory">Удалить</button>
                    </div>
                </div>
            </div>
        </div>



        {{--        save-qualification--}}
        <div class="modal-container" v-if="qualifications_flag === true" id="createQualificationModal">
            <div class="modal-inside">
                <form id="StoreQualificationForm" @submit.prevent="storeQualification">
                    <div class="title">
                        <h1>Создание квалификации</h1>
                    </div>

                    <div :class="message_create_qualification ? 'alert-success':''">
                        @{{ message_create_qualification }}
                    </div>

                    <div class="mb-1">
                        <input type="text" name="title" id="title" class="form-settings" placeholder="Название" :class="errors_qualification.title ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_qualification.title">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="create_qualification_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        edit-qualification--}}
        <div class="modal-container" v-if="qualifications_flag === true" id="editQualificationModal">
            <div class="modal-inside">
                <form id="editQualificationForm" @submit.prevent="editQualification">
                    <div class="title">
                        <h1>Изменение квалификации</h1>
                    </div>

                    <div :class="message_edit_qualification ? 'alert-success':''">
                        @{{ message_edit_qualification }}
                    </div>

                    <div class="mb-1">
                        <input type="text" name="title_edit" v-model="qualification_obj_edit.title" id="title_edit"
                               class="form-settings" placeholder="Название" :class="errors_qualification.title_edit ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_qualification.title_edit">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="edit_qualification_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-qualification--}}
        <div class="modal-container" v-if="qualifications_flag === true" id="deleteQualificationModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите удалить эту квалификацию? Это действие невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_qualification_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteQualification">Удалить</button>
                    </div>
                </div>
            </div>
        </div>



        {{--        save-master--}}
        <div class="modal-container" v-if="masters_flag === true" id="createMasterModal">
            <div class="modal-inside">
                <form id="StoreMasterForm" @submit.prevent="storeMaster">
                    <div class="title">
                        <h1>Добавление мастера</h1>
                    </div>

                    <div :class="message_create_masters ? 'alert-success':''">
                        @{{ message_create_masters }}
                    </div>

                    <div :class="message_create_masters_error ? 'alert-error':''">
                        @{{ message_create_masters_error }}
                    </div>

                    <div class="mb-03">

                        <select name="user" id="user" class="form-settings select-form select-form-light"
                                :class="errors_masters.user ? 'is-invalid':''">
                            <option value="0" selected>Пользователь</option>
                            <option v-for="user in users" :value="user.id">@{{ user.name }} @{{ user.surname }}, @{{ user.tel }}</option>
                        </select>

                        <div class="invalid-feedback" v-for="error in errors_masters.user">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-03">
                        <input type="text" name="specialization" id="specialization" class="form-settings" placeholder="Специализация"
                               :class="errors_masters.specialization ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_masters.specialization">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-03">

                        <select name="qualification" id="qualification" class="form-settings select-form select-form-light"
                                :class="errors_masters.qualification ? 'is-invalid':''">
                            <option value="0" selected>Квалификация</option>
                            <option v-for="qualification in qualifications" :value="qualification.id">@{{ qualification.title }}</option>
                        </select>

                        <div class="invalid-feedback" v-for="error in errors_masters.qualification">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">

                        <textarea rows="6" name="description" id="description" class="form-settings textarea-settings"
                                  :class="errors_masters.description ? 'is-invalid':''" placeholder="Описание"></textarea>

                        <div class="invalid-feedback" v-for="error in errors_masters.description">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="create_master_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        edit-master--}}
        <div class="modal-container" v-if="masters_flag === true" id="editMasterModal">
            <div class="modal-inside">
                <form id="EditMasterForm" @submit.prevent="editMaster">
                    <div class="title">
                        <h1>Изменение информации о мастере @{{ master_obj_edit.name }}</h1>
                    </div>

                    <div :class="message_edit_masters ? 'alert-success':''">
                        @{{ message_edit_masters }}
                    </div>

                    <div class="mb-03">
                        <input v-model="master_obj_edit.specialization" type="text" name="specialization_edit"
                               id="specialization_edit" class="form-settings" placeholder="Специализация"
                               :class="errors_masters.specialization_edit ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_masters.specialization_edit">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-03">

                        <select v-model="master_obj_edit.qualification" name="qualification_edit" id="qualification_edit"
                                class="form-settings select-form select-form-light"
                                :class="errors_masters.qualification_edit ? 'is-invalid':''">
                            <option v-for="qualification in qualifications" :value="qualification.id">@{{ qualification.title }}</option>
                        </select>

                        <div class="invalid-feedback" v-for="error in errors_masters.qualification_edit">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">

                        <textarea v-model="master_obj_edit.description" rows="6" name="description_edit" id="description_edit"
                                  class="form-settings textarea-settings" :class="errors_masters.description_edit ? 'is-invalid':''"
                                  placeholder="Описание"></textarea>

                        <div class="invalid-feedback" v-for="error in errors_masters.description_edit">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="edit_master_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-master--}}
        <div class="modal-container" v-if="masters_flag === true" id="deleteMasterModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите убрать у этого пользователя роль мастера?
                        Это действие невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_master_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteMaster">Удалить</button>
                    </div>
                </div>
            </div>
        </div>



        {{--        save-admin--}}
        <div class="modal-container" v-if="admins_flag === true" id="createAdminModal">
            <div class="modal-inside">
                <form id="StoreAdminForm" @submit.prevent="storeAdmin">
                    <div class="title">
                        <h1>Добавление администратора</h1>
                    </div>

                    <div :class="message_create_admins ? 'alert-success':''">
                        @{{ message_create_admins }}
                    </div>

                    <div :class="message_create_admins_error ? 'alert-error':''">
                        @{{ message_create_admins_error }}
                    </div>

                    <div class="mb-1">
                        <select name="user" id="user" class="form-settings select-form select-form-light">
                            <option value="0" selected>Пользователь</option>
                            <option v-for="user in users" :value="user.id">@{{ user.name }} @{{ user.surname }}, @{{ user.tel }}</option>
                        </select>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="create_admin_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-admin--}}
        <div class="modal-container" v-if="admins_flag === true" id="deleteAdminModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите убрать у этого пользователя роль администратора?
                        Это действие невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_admin_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteAdmin">Удалить</button>
                    </div>
                </div>
            </div>
        </div>



        {{--        save-service--}}
        <div class="modal-container" v-if="services_flag === true" id="createServiceModal">
            <div class="modal-inside">
                <form id="StoreServiceForm" @submit.prevent="storeService">
                    <div class="title">
                        <h1>Добавление услуги</h1>
                    </div>

                    <div :class="message_create_services ? 'alert-success':''">
                        @{{ message_create_services }}
                    </div>

                    <div :class="message_create_services_error ? 'alert-error':''">
                        @{{ message_create_services_error }}
                    </div>

                    <div class="mb-03">
                        <input type="text" name="title" id="title" class="form-settings" placeholder="Название"
                               :class="errors_services.title ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_services.title">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-03">

                        <select name="category" id="category" class="form-settings select-form select-form-light"
                                :class="errors_services.category ? 'is-invalid':''">
                            <option value="0" selected>Категория</option>
                            <option v-for="category in categories" :value="category.id">@{{ category.title }}</option>
                        </select>

                        <div class="invalid-feedback" v-for="error in errors_services.category">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">

                        <textarea rows="6" name="description" id="description" class="form-settings textarea-settings"
                                  :class="errors_services.description ? 'is-invalid':''" placeholder="Описание (необязательно)"></textarea>

                    </div>

                    <div class="mb-1" v-for="(service_qualification, index) in service_qualifications">
                        <div class="mb-03" :class="service_qualifications.length > 1 ? 'space-between-stretch':''">
                            <div :class="service_qualifications.length > 1 ? 'long-input':''">
                                <select name="qualification[]" id="`qualification_${index}`"
                                        class="form-settings select-form select-form-light"
                                        :class="errors_services.qualification ? 'is-invalid':''">
                                    <option value="0" selected>Квалификация мастера</option>
                                    <option v-for="qualification in qualifications" :value="qualification.id">@{{ qualification.title }}</option>
                                </select>

                                <div class="invalid-feedback" v-for="error in errors_services.qualification">
                                    @{{ error }}
                                </div>
                            </div>

                            <button v-if="service_qualifications.length > 1" type="button"
                                    class="danger-btn btn-empty-svg" @click="delete_qualification(index)">
                                <svg width="26" height="5" viewBox="0 0 26 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24 2.40821L2 2.4082" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mb-03 space-between-end">
                            <div class="" style="width: 49%">
                                <input type="text" name="duration[]" id="`duration_${index}`" class="form-settings"
                                       placeholder="Длительность (в мин)"
                                       :class="errors_services.duration ? 'is-invalid':''">

                                <div class="invalid-feedback" v-for="error in errors_services.duration">
                                    @{{ error }}
                                </div>
                            </div>

                            <div class="" style="width: 49%">
                                <input type="text" name="price[]" id="`price_${index}`" class="form-settings" placeholder="Стоимость"
                                       :class="errors_services.price ? 'is-invalid':''">

                                <div class="invalid-feedback" v-for="error in errors_services.price">
                                    @{{ error }}
                                </div>
                            </div>
                        </div>

                        <div class="space-between-stretch mb-03" v-for="(service_master, index2) in service_qualification">
                            <div :class="service_qualification.length > 1 ? 'short-input':'long-input'">
                                <select name="master[]" id="`master_${index}_${index2}`"
                                        class="form-settings select-form select-button select-form-light">
                                    <option value="0" selected>Мастер</option>
                                    <option v-for="master in masters" :value="master.id">
                                        @{{ master.name }}, @{{ master.specialization }}, @{{ master.qualification.title }}
                                    </option>
                                </select>
                            </div>

                            <div class="space-between-stretch">
                                <button style="margin-right: 0.3rem" v-if="service_qualification.length > 1" type="button"
                                        class="danger-btn btn-empty-svg" @click="delete_master(index, index2)">
                                    <svg width="26" height="5" viewBox="0 0 26 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M24 2.40821L2 2.4082" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                </button>

                                <button type="button" class="btn-empty btn-empty-svg" @click="add_master(index)">
                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 2L13 24M24 13.4074L2 13.4074" stroke="#BC13FE" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-1" v-if="service_qualifications.length < qualifications.length">
                        <button type="button" class="btn-empty" @click="add_qualification">Добавить квалификацию</button>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="create_service_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        edit-service--}}
        <div class="modal-container" v-if="services_flag === true" id="editServiceModal">
            <div class="modal-inside">
                <form id="EditServiceForm" @submit.prevent="editService">
                    <div class="title">
                        <h1>Изменение услуги</h1>
                    </div>

                    <div :class="message_edit_services ? 'alert-success':''">
                        @{{ message_edit_services }}
                    </div>

                    <div :class="message_edit_services_error ? 'alert-error':''">
                        @{{ message_edit_services_error }}
                    </div>

                    <div class="mb-03">
                        <input type="text" v-model="service_obj_edit.title" name="title_edit" id="title_edit"
                               class="form-settings" placeholder="Название" :class="errors_services.title_edit ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_services.title_edit">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-03">
                        <select name="category_edit" v-model="service_obj_edit.category_id" id="category_edit"
                                class="form-settings select-form select-form-light"
                                :class="errors_services.category_edit ? 'is-invalid':''">
                            <option v-for="category in categories" :value="category.id">@{{ category.title }}</option>
                        </select>
                    </div>

                    <div class="mb-1">

                        <textarea rows="6" v-model="service_obj_edit.description" name="description_edit" id="description_edit"
                                  class="form-settings textarea-settings" :class="errors_services.description_edit ? 'is-invalid':''"
                                  placeholder="Описание (необязательно)"></textarea>
                    </div>

                    <div class="mb-1" v-for="(service_qualification, index) in service_obj_edit.qualifications">
                        <div class="mb-03" :class="service_obj_edit.qualifications.length > 1 ? 'space-between-stretch':''">
                            <div :class="service_obj_edit.qualifications.length > 1 ? 'long-input':''">
                                <select name="qualification_edit[]" id="`qualification_edit_${index}`"
                                        class="form-settings select-form select-form-light"
                                        v-model="service_qualification.qualification_id"
                                        :class="errors_services.qualification_edit ? 'is-invalid':''">
                                    <option value="0" selected>Квалификация мастера</option>
                                    <option v-for="qualification in qualifications" :value="qualification.id">@{{ qualification.title }}</option>
                                </select>

                                <div class="invalid-feedback" v-for="error in errors_services.qualification_edit">
                                    @{{ error }}
                                </div>
                            </div>

                            <button v-if="service_obj_edit.qualifications.length > 1" type="button"
                                    class="danger-btn btn-empty-svg" @click="delete_qualification_edit(index)">
                                <svg width="26" height="5" viewBox="0 0 26 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24 2.40821L2 2.4082" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mb-03 space-between-end">
                            <div class="" style="width: 49%">
                                <input type="text" name="duration_edit[]" id="`duration_edit_${index}`" class="form-settings"
                                       placeholder="Длительность (в минутах)" :class="errors_services.duration_edit ? 'is-invalid':''"
                                       v-model="service_qualification.duration">

                                <div class="invalid-feedback" v-for="error in errors_services.duration_edit">
                                    @{{ error }}
                                </div>
                            </div>

                            <div class="" style="width: 49%">
                                <input type="text" name="price_edit[]" id="`price_edit_${index}`" class="form-settings"
                                       placeholder="Стоимость" :class="errors_services.price_edit ? 'is-invalid':''"
                                       v-model="service_qualification.price">

                                <div class="invalid-feedback" v-for="error in errors_services.price_edit">
                                    @{{ error }}
                                </div>
                            </div>
                        </div>

                        <div class="space-between-stretch mb-03" v-for="(qualification_master, index2) in service_qualification.masters">
                            <div :class="service_qualification.masters.length > 1 ? 'short-input':'long-input'">
                                <select name="master_edit[]" id="`master_edit_${index}_${index2}`"
                                        class="form-settings select-form select-button select-form-light"
                                        :class="errors_services.master_edit ? 'is-invalid':''" v-model="qualification_master.id">
                                    <option value="0" selected>Мастер</option>
                                    <option v-for="master in masters" :value="master.id">
                                        @{{ master.name }}, @{{ master.specialization }}, @{{ master.qualification.title }}
                                    </option>
                                </select>

                                <div class="invalid-feedback" v-for="error in errors_services.master_edit">
                                    @{{ error }}
                                </div>
                            </div>

                            <div class="space-between-stretch">
                                <button style="margin-right: 5px" v-if="service_qualification.masters.length > 1" type="button"
                                        class="danger-btn btn-empty-svg" @click="delete_master_edit(index, index2)">
                                    <svg width="26" height="5" viewBox="0 0 26 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M24 2.40821L2 2.4082" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                </button>

                                <button type="button" class="btn-empty btn-empty-svg" @click="add_master_edit(index)">
                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 2L13 24M24 13.4074L2 13.4074" stroke="#BC13FE" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>

                        </div>

                    </div>

                    <div class="mb-1" v-if="service_obj_edit.qualifications.length < qualifications.length">
                        <button type="button" class="btn-empty" @click="add_qualification_edit">Добавить квалификацию</button>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="edit_service_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-service--}}
        <div class="modal-container" v-if="services_flag === true" id="deleteServiceModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите удалить эту услугу? Это действие невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_service_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteService">Удалить</button>
                    </div>
                </div>
            </div>
        </div>



        {{--        save-calendar--}}
        <div class="modal-container" v-if="calendar_flag === true" id="createCalendarModal">
            <div class="modal-inside">
                <form id="StoreCalendarForm" @submit.prevent="storeCalendar">
                    <div class="title">
                        <h1>Добавление, изменение и удаление свободных окошек</h1>
                    </div>

                    <div :class="message_create_calendar ? 'alert-success':''">
                        @{{ message_create_calendar }}
                    </div>

                    <div :class="message_create_calendar_error ? 'alert-error':''">
                        @{{ message_create_calendar_error }}
                    </div>

                    <div class="mb-03 space-between-end">
                        <div class="" style="width: 49%">
                            <select name="master" id="master" class="form-settings select-form select-form-light"
                                    @change="getInfoMonth" v-model="master_id">
                                <option value="0" selected>Мастер</option>
                                <option v-for="master in masters" :value="master.id">@{{ master.name }}, @{{ master.specialization }}</option>
                            </select>

                        </div>

                        <div class="" style="width: 49%">
                            <select name="month" id="month" class="form-settings select-form select-form-light"
                                    v-model="month_id" @change="getInfoMonth">
                                <option value="0" selected>Месяц</option>
                                <option v-for="month in months" :value="`${month.number_month}_${month.year}`">
                                    @{{ month.name_month }}, @{{ month.year }}</option>
                            </select>
                        </div>
                    </div>

                    <table class="mb-03 calendar" v-if="month_id != '0' && master_id != '0'">
                        <thead>
                        <tr>
                            <td class="weekday"><p>Пн</p></td>
                            <td class="weekday"><p>Вт</p></td>
                            <td class="weekday"><p>Ср</p></td>
                            <td class="weekday"><p>Чт</p></td>
                            <td class="weekday"><p>Пт</p></td>
                            <td class="weekends"><p>Сб</p></td>
                            <td class="weekends"><p>Вс</p></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td v-for="skip in selected_mouth.skipDays" :key="skip"></td>
                            <td v-for="day in (7 - selected_mouth.skipDays)">
                                <button :id="`${day}`" type="button" class="btn-calendar" @click="getFocusBtn(day)"
                                        :class="(btnHidden(day) ? 'btn-calendar-hidden-light':'') || (btnColor(day) ? 'btn-calendar-date-yes':'')">
                                    @{{ day }}</button>
                            </td>
                        </tr>
                        <tr v-for="(week, index_week) in (Math.floor(selected_mouth.days / 7))">
                            <td v-for="day in 7" :key="(7 - selected_mouth.skipDays) + day" v-if="index_week === 0">
                                <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + day}`" type="button" class="btn-calendar"
                                        @click="getFocusBtn((7 - selected_mouth.skipDays) + day)"
                                        :class="(btnHidden((7 - selected_mouth.skipDays) + day) ? 'btn-calendar-hidden-light':'')
                                        || (btnColor((7 - selected_mouth.skipDays) + day) ? 'btn-calendar-date-yes':'')">
                                    @{{ (7 - selected_mouth.skipDays) + day }}</button>
                            </td>
                            <td v-for="day in 7" :key="((7 - selected_mouth.skipDays) + (index_week * 7)) + day" v-if="(index_week !== 0)">
                                <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + (index_week * 7) + day}`"
                                        type="button" class="btn-calendar" @click="getFocusBtn((7 - selected_mouth.skipDays) + (index_week * 7) + day)"
                                        v-if="(((7 - selected_mouth.skipDays) + (index_week * 7) + day) <= selected_mouth.days)"
                                        :class="(btnHidden((7 - selected_mouth.skipDays) + (index_week * 7) + day) ? 'btn-calendar-hidden-light':'')
                                        || (btnColor((7 - selected_mouth.skipDays) + (index_week * 7) + day) ? 'btn-calendar-date-yes':'')  ">
                                    @{{ (7 - selected_mouth.skipDays) + (index_week * 7) + day }}</button>
                            </td>
                        </tr>

                        <tr v-if="(Math.floor(selected_mouth.days / 7) * 7) + (7 - selected_mouth.skipDays) < selected_mouth.days">
                            <td v-for="day in 7" :key="(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day">
                                <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day}`"
                                        type="button" class="btn-calendar"
                                        @click="getFocusBtn((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day)"
                                        v-if="(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day <= selected_mouth.days"
                                        :class="(btnHidden((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day) ? 'btn-calendar-hidden-light':'')
                                        || (btnColor((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day) ? 'btn-calendar-date-yes':'')">
                                    @{{ (7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day }}</button>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <div class="mb-1">
                        <div class="space-between-stretch mb-03"
                             v-if="focus_btn_id != 0 && month_id != 0 && btnColor(focus_btn_id) === false"
                             v-for="(time, time_index) in times">
                            <div :class="times.length > 1 ? 'short-input':'long-input'">
                                <input type="time" name="time[]" :id="`time_${selected_mouth.number_month}_${focus_btn_id}_${time_index}`"
                                       class="form-settings" :class="errors_calendar.time ? 'is-invalid':''">

                                <div class="invalid-feedback" v-for="error in errors_calendar.time">
                                    @{{ error }}
                                </div>
                            </div>

                            <div class="space-between-stretch">
                                <button style="margin-right: 0.3rem" v-if="times.length > 1" type="button"
                                        class="danger-btn btn-empty-svg" @click="delete_time(time_index)">
                                    <svg width="26" height="5" viewBox="0 0 26 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M24 2.40821L2 2.4082" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                </button>

                                <button type="button" class="btn-empty btn-empty-svg" @click="add_time">
                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 2L13 24M24 13.4074L2 13.4074" stroke="#BC13FE" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-03" v-if="month_id != 0 && date_times != [''] && btnColor(focus_btn_id)"
                             v-for="(time, time_index) in date_times">
                            <div class="space-between-stretch">
                                <div :class="date_times.length > 1 ? 'short-input':'long-input'">
                                    <input v-model="time[0]" type="time" name="time[]"
                                           :id="`time_${selected_mouth.number_month}_${focus_btn_id}_${time_index}`"
                                           class="form-settings" :class="errors_calendar.time ? 'is-invalid':''">

                                    <div class="invalid-feedback" v-for="error in errors_calendar.time">
                                        @{{ error }}
                                    </div>
                                </div>

                                <div class="space-between-stretch">
                                    <button style="margin-right: 0.3rem" v-if="date_times.length > 1" type="button"
                                            class="danger-btn btn-empty-svg" @click="delete_time_edit(time_index)">
                                        <svg width="26" height="5" viewBox="0 0 26 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M24 2.40821L2 2.4082" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                        </svg>
                                    </button>

                                    <button type="button" class="btn-empty btn-empty-svg" @click="add_time_edit">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 2L13 24M24 13.4074L2 13.4074" stroke="#BC13FE" stroke-width="4" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="create_calendar_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-calendar--}}
        <div class="modal-container" v-if="calendar_flag === true" id="deleteCalendarModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите удалить расписание на данный месяц у этого мастера?
                        Это действие приведёт к удалению всех свободных, занятых и проведённых записей.
                        Его невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_calendar_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteCalendar">Удалить</button>
                    </div>
                </div>
            </div>
        </div>


        {{--        edit-status-application--}}
        <div class="modal-container" v-if="applications_flag === true" id="editApplicationModal">
            <div class="modal-inside">
                <form id="EditCategoryForm" @submit.prevent="editStatusApplication">
                    <div class="title">
                        <h1>Изменение статуса записи</h1>
                    </div>

                    <div :class="message_edit_application ? 'alert-success':''">
                        @{{ message_edit_application }}
                    </div>

                    <div class="mb-1">
                        <select v-model="application_obj.status" name="status" id="status"
                                class="form-settings select-form select-form-light">
                            <option value="Запланировано">Запланировано</option>
                            <option value="Отменено">Отменено</option>
                        </select>
                    </div>


                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="edit_application_modal">Отмена</button>
                        <button class="btn-full-form">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {
                    categories_flag: true,
                    qualifications_flag: false,
                    masters_flag: false,
                    admins_flag: false,
                    services_flag: false,
                    calendar_flag: false,
                    applications_flag: false,

                    message_create_category: '',
                    message_edit_category: '',
                    errors_category: [],
                    categories: [],
                    category_obj_edit: {
                        id: 0,
                        title: ''
                    },
                    category_open_short: false,
                    search_category: '',

                    message_create_qualification: '',
                    message_edit_qualification: '',
                    errors_qualification: [],
                    qualifications: [],
                    qualification_obj_edit: {
                        id: 0,
                        title: ''
                    },
                    qualification_open_short: false,
                    search_qualification: '',

                    message_create_masters: '',
                    message_create_masters_error: '',
                    message_edit_masters: '',
                    errors_masters: [],
                    masters: [],
                    master_obj_edit: {
                        id: 0,
                        name: '',
                        specialization: '',
                        qualification: '',
                        description: ''
                    },
                    master_open_short: false,
                    search_master: '',
                    master_filter_id: 0,

                    message_create_admins: '',
                    message_create_admins_error: '',
                    message_edit_admins: '',
                    admins: [],
                    admin_obj_edit: {
                        id: 0,
                        name: '',
                    },
                    admin_open_short: false,
                    search_admin: '',

                    message_create_services: '',
                    message_create_services_error: '',
                    message_edit_services: '',
                    message_edit_services_error: '',
                    errors_services: [],
                    services: [],
                    service_obj_edit: {
                        id: 0,
                        title: '',
                        category_id: 0,
                        description: '',
                        qualifications: [],
                    },
                    service_qualifications: [['']],
                    service_open_short: false,
                    search_service: '',
                    service_filter_id: 0,

                    message_create_calendar: '',
                    message_create_calendar_error: '',
                    message_edit_calendar_error: '',
                    message_edit_calendar: '',
                    errors_calendar: [],
                    calendars: [],
                    calendar_show: {
                        id: 0,
                        month_name: '',
                        month_number: 0,
                        master_id: 0,
                        master_name: '',
                        year: '',
                        calendar: [],
                    },
                    selected_mouth_show: '',
                    calendar_open_short: false,
                    search_calendar: '',
                    calendar_filter_id: 0,

                    message_edit_application: '',
                    applications: [],
                    application_open_short: false,
                    applications_status_filter_id: 0,
                    application_obj: {
                        id: 0,
                        status: '',
                        date: '',
                        time: '',
                        master_id: 0
                    },

                    months: [],
                    users: [],
                    delete_id: 0,
                    month_id: 0,
                    selected_mouth: '',
                    focus_btn_id: 0,
                    times: [''],
                    master_id: 0,
                    calendar: '',
                    date_times: [''],
                    day_id: 0,

                    theme: localStorage.getItem('theme') || 'light'

                }
            },

            methods: {

                // get-methods
                async getCategories() {
                    const response = await fetch('{{route('getCategories')}}');
                    this.categories = await response.json();
                },
                async getQualifications() {
                    const response = await fetch('{{route('getQualifications')}}');
                    this.qualifications = await response.json();
                },
                async getUsers() {
                    const response = await fetch('{{route('getUsers')}}');
                    this.users = await response.json();
                },
                async getMasters() {
                    const response = await fetch('{{route('getAllMasters')}}');
                    this.masters = await response.json();
                },
                async getAdmins() {
                    const response = await fetch('{{route('getAdmins')}}');
                    this.admins = await response.json();
                },
                async getServices() {
                    const response = await fetch('{{route('getServices')}}');
                    this.services = await response.json();
                },
                async getCalendars() {
                    const response = await fetch('{{route('getCalendars')}}');
                    this.calendars = await response.json();
                    this.calendars = this.calendars.map(calendar => {
                        calendar.dates.sort((date1,date2) => Number(Object.keys(date1)[0]) - Number(Object.keys(date2)[0]));
                        return calendar;
                    });
                },
                async getApplications() {
                    const response = await fetch('{{route('getApplications')}}');
                    this.applications = await response.json();
                },
                getMonths() {
                    this.months = [];
                    let today = new Date();
                    let monthToday = today.getMonth();
                    let yearToday = today.getFullYear();

                    for (let i = 1; i <= 4; i++) {
                        let date = new Date(yearToday, monthToday + i - 1, 1);
                        let skipDays = new Date(yearToday, monthToday + i - 1, 1).getDay() - 1;

                        if (skipDays === -1) {
                            skipDays = 6;
                        }

                        let monthDays = new Date(yearToday, monthToday + i, 0).getDate();

                        let monthName = date.toLocaleString('ru', { month: 'long' });
                        monthName = monthName[0].toUpperCase() + monthName.slice(1);

                        if (monthToday + i > 12) {
                            this.months.push({
                                number_month: (monthToday + i) - 12,
                                name_month: monthName,
                                days: monthDays,
                                year: yearToday,
                                skipDays: skipDays
                            });
                        } else {
                            this.months.push({
                                number_month: monthToday + i,
                                name_month: monthName,
                                days: monthDays,
                                year: yearToday,
                                skipDays: skipDays
                            });
                        }

                    }
                },


                //store-methods
                async storeCategory() {
                    let form = document.getElementById('StoreCategoryForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('saveCategory')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });
                    if(response.status === 201) {
                        this.message_create_category = await response.json();
                        this.errors_category = [];
                        form.reset();
                        this.getCategories();
                    }
                    if(response.status === 400) {
                        this.errors_category = await response.json();
                        this.message_create_category = '';
                    }
                },
                async storeQualification() {
                    let form = document.getElementById('StoreQualificationForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('saveQualification')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 201) {
                        this.message_create_qualification = await response.json();
                        this.errors_qualification = [];
                        form.reset();
                        this.getQualifications();
                    }

                    if(response.status === 400) {
                        this.errors_qualification = await response.json();
                        this.message_create_qualification = '';
                    }

                },
                async storeMaster() {
                    let form = document.getElementById('StoreMasterForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('saveMaster')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.message_create_masters_error = '';
                        this.errors_masters = [];
                        this.message_create_masters = await response.json();
                        form.reset();
                        this.getMasters();
                        this.getUsers();
                    }

                    if(response.status === 400) {
                        this.errors_masters = await response.json();
                        this.message_create_masters_error = '';
                        this.message_create_masters = '';
                    }

                    if(response.status === 422) {
                        this.errors_masters = [];
                        this.message_create_masters_error = await response.json();
                        this.message_create_masters = '';
                    }

                },
                async storeAdmin() {
                    let form = document.getElementById('StoreAdminForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('saveAdmin')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.message_create_admins_error = '';
                        this.message_create_admins = await response.json();
                        form.reset();
                        this.getAdmins();
                        this.getUsers();
                    }

                    if(response.status === 400) {
                        this.message_create_admins_error = await response.json();
                        this.message_create_admins = '';
                    }

                },
                async storeService() {
                    let form = document.getElementById('StoreServiceForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('saveService')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 201) {
                        this.message_create_services_error = '';
                        this.message_create_services = await response.json();
                        form.reset();
                        this.getServices();
                        this.service_qualifications = [['']];
                    }

                    if(response.status === 400) {
                        this.errors_services = await response.json();
                        this.message_create_services = '';
                        this.message_create_services_error = '';
                    }

                    if(response.status === 422) {
                        this.message_create_services_error = await response.json();
                        this.message_create_services = '';
                        this.errors_services = [];
                    }

                },
                async storeCalendar() {
                    let form = document.getElementById('StoreCalendarForm');
                    let data = new FormData(form);
                    data.append('day', this.focus_btn_id);
                    data.append('month_name', this.selected_mouth.name_month);
                    const response = await fetch('{{route('saveCalendar')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 201) {
                        this.message_create_calendar_error = '';
                        this.message_create_calendar = await response.json();
                        this.getCalendarDay();
                        this.times = [''];
                    }

                    if(response.status === 400) {
                        this.message_create_calendar_error = await response.json();
                        this.message_create_calendar = '';
                    }
                },


                //edit-methods
                async editCategory() {
                    let form = document.getElementById('EditCategoryForm');
                    let data = new FormData(form);
                    data.append('id', this.category_obj_edit.id);
                    const response = await fetch('{{route('editCategory')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });
                    if(response.status === 200) {
                        this.message_edit_category = await response.json();
                        this.errors_category = [];
                        this.getCategories();
                    }
                    if(response.status === 400) {
                        this.errors_category = await response.json();
                        this.message_edit_category = '';
                    }
                },
                async editQualification() {
                    let form = document.getElementById('editQualificationForm');
                    let data = new FormData(form);
                    data.append('id', this.qualification_obj_edit.id);
                    const response = await fetch('{{route('editQualification')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.message_edit_qualification = await response.json();
                        this.errors_qualification = [];
                        this.getQualifications();
                    }

                    if(response.status === 400) {
                        this.errors_qualification = await response.json();
                        this.message_edit_qualification = '';
                    }

                },
                async editMaster() {
                    let form = document.getElementById('EditMasterForm');
                    let data = new FormData(form);
                    data.append('id', this.master_obj_edit.id);
                    const response = await fetch('{{route('editMaster')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.errors_masters = [];
                        this.message_edit_masters = await response.json();
                        this.getMasters();
                        this.getUsers();
                    }

                    if(response.status === 400) {
                        this.errors_masters = await response.json();
                        this.message_edit_masters = '';
                    }

                },
                async editService() {
                    let form = document.getElementById('EditServiceForm');
                    let data = new FormData(form);
                    data.append('id', this.service_obj_edit.id);
                    const response = await fetch('{{route('editService')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.errors_services = [];
                        this.message_edit_services_error = '';
                        this.message_edit_services = await response.json();
                        form.reset();
                        this.getServices();
                    }

                    if(response.status === 400) {
                        this.errors_services = await response.json();
                        this.message_edit_services = '';
                        this.message_edit_services_error = '';
                    }

                },
                async editStatusApplication() {
                    let data = new FormData();
                    data.append('id', this.application_obj.id);
                    data.append('date', this.application_obj.date);
                    data.append('time', this.application_obj.time);
                    data.append('master_id', this.application_obj.master_id);
                    data.append('status', this.application_obj.status);
                    const response = await fetch('{{route('editStatusApplication')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.message_edit_application = await response.json();
                        this.getApplications();
                        this.getCalendars();
                        this.filterApplications;
                    }

                },


                //delete-methods
                async deleteCategory() {
                    const response = await fetch('{{route('deleteCategory')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });
                    if(response.status === 200) {
                        this.delete_category_modal();
                        this.getCategories();
                    }
                },
                async deleteQualification() {
                    const response = await fetch('{{route('deleteQualification')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });

                    if(response.status === 200) {
                        this.delete_qualification_modal();
                        this.getQualifications();
                    }

                },
                async deleteMaster() {
                    const response = await fetch('{{route('deleteMaster')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });

                    if(response.status === 200) {
                        this.delete_master_modal();
                        this.getMasters();
                        this.getUsers();
                    }

                },
                async deleteAdmin() {
                    const response = await fetch('{{route('deleteAdmin')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });

                    if(response.status === 200) {
                        this.delete_admin_modal();
                        this.getAdmins();
                        this.getUsers();
                    }

                },
                async deleteService() {
                    const response = await fetch('{{route('deleteService')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });

                    if(response.status === 200) {
                        this.delete_service_modal();
                        this.getServices();
                    }

                },
                async deleteCalendar() {
                    const response = await fetch('{{route('deleteCalendar')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });

                    if(response.status === 200) {
                        this.delete_calendar_modal();
                        this.getCalendars();
                    }

                },


                //create-modal
                create_category_modal() {
                    document.getElementById('createCategoryModal').classList.toggle('modal-container-opacity');
                    this.message_create_category = '';
                },
                create_qualification_modal() {
                    document.getElementById('createQualificationModal').classList.toggle('modal-container-opacity');
                    this.message_create_qualification = '';
                },
                create_master_modal() {
                    document.getElementById('createMasterModal').classList.toggle('modal-container-opacity');
                    this.message_create_masters = '';
                },
                create_admin_modal() {
                    document.getElementById('createAdminModal').classList.toggle('modal-container-opacity');
                    this.message_create_admins = '';
                },
                create_service_modal() {
                    document.getElementById('createServiceModal').classList.toggle('modal-container-opacity');
                    this.message_create_services = '';
                },
                create_calendar_modal() {
                    document.getElementById('createCalendarModal').classList.toggle('modal-container-opacity');
                    this.message_create_calendar = '';
                },


                //edit-modal
                edit_category_modal(category) {
                    document.getElementById('editCategoryModal').classList.toggle('modal-container-opacity');
                    this.category_obj_edit.id = category.id;
                    this.category_obj_edit.title = category.title;
                    this.message_edit_category = '';
                },
                edit_qualification_modal(qualification) {
                    document.getElementById('editQualificationModal').classList.toggle('modal-container-opacity');
                    this.qualification_obj_edit.id = qualification.id;
                    this.qualification_obj_edit.title = qualification.title;
                    this.message_edit_qualification = '';

                },
                edit_master_modal(master) {
                    document.getElementById('editMasterModal').classList.toggle('modal-container-opacity');
                    this.master_obj_edit.id = master.id;
                    this.master_obj_edit.name = master.name;
                    this.master_obj_edit.specialization = master.specialization;
                    this.master_obj_edit.qualification = master.qualification_id;
                    this.master_obj_edit.description = master.description;
                    this.message_edit_masters = '';
                },
                edit_service_modal(service) {
                    document.getElementById('editServiceModal').classList.toggle('modal-container-opacity');
                    this.service_obj_edit.id = service.id;
                    this.service_obj_edit.title = service.title;
                    this.service_obj_edit.category_id = service.category_id;
                    this.service_obj_edit.description = service.description;
                    this.service_obj_edit.qualifications = [];
                    for (let i = 0; i < service.qualifications.length; i++) {
                        let qualification = {
                            qualification_id: service.qualifications[i].id,
                            duration: service.qualifications[i].duration,
                            price: service.qualifications[i].price,
                            masters: []
                        }

                        for (let j = 0; j < service.masters.length; j++) {
                            if (service.masters[j].qualification_id === qualification.qualification_id) {
                                qualification.masters.push(service.masters[j]);
                            }
                        }
                        this.service_obj_edit.qualifications.push(qualification);
                    }
                },
                edit_application_modal(application) {
                    document.getElementById('editApplicationModal').classList.toggle('modal-container-opacity');
                    if (application.id) {
                        this.application_obj.id = application.id;
                        this.application_obj.status = application.status;
                        this.application_obj.master_id = application.master.id;
                        this.application_obj.date = application.date;
                        this.application_obj.time = application.time;
                    }
                },


                //delete-modal
                delete_category_modal(id) {
                    document.getElementById('deleteCategoryModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
                },
                delete_qualification_modal(id) {
                    document.getElementById('deleteQualificationModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
                },
                delete_master_modal(id) {
                    document.getElementById('deleteMasterModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
                },
                delete_admin_modal(id) {
                    document.getElementById('deleteAdminModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
                },
                delete_service_modal(id) {
                    document.getElementById('deleteServiceModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
                },
                delete_calendar_modal(id) {
                    document.getElementById('deleteCalendarModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
                },


                open_categories() {
                    if(this.categories_flag === false) {
                        this.qualifications_flag = false;
                        this.masters_flag = false;
                        this.admins_flag = false;
                        this.services_flag = false;
                        this.calendar_flag = false;
                        this.applications_flag = false;
                        this.categories_flag = true;
                    }
                },
                open_qualifications() {
                    if(this.qualifications_flag === false) {
                        this.masters_flag = false;
                        this.admins_flag = false;
                        this.services_flag = false;
                        this.calendar_flag = false;
                        this.categories_flag = false;
                        this.applications_flag = false;
                        this.qualifications_flag = true;
                    }
                },
                open_masters() {
                    if(this.masters_flag === false) {
                        this.admins_flag = false;
                        this.services_flag = false;
                        this.calendar_flag = false;
                        this.categories_flag = false;
                        this.qualifications_flag = false;
                        this.applications_flag = false;
                        this.masters_flag = true;
                    }
                },
                open_admins() {
                    if(this.admins_flag === false) {
                        this.services_flag = false;
                        this.calendar_flag = false;
                        this.categories_flag = false;
                        this.qualifications_flag = false;
                        this.masters_flag = false;
                        this.applications_flag = false;
                        this.admins_flag = true;
                    }
                },
                open_services() {
                    if(this.services_flag === false) {
                        this.calendar_flag = false;
                        this.categories_flag = false;
                        this.qualifications_flag = false;
                        this.masters_flag = false;
                        this.admins_flag = false;
                        this.applications_flag = false;
                        this.services_flag = true;
                    }
                },
                open_calendar() {
                    if(this.calendar_flag === false) {
                        this.categories_flag = false;
                        this.qualifications_flag = false;
                        this.masters_flag = false;
                        this.admins_flag = false;
                        this.services_flag = false;
                        this.applications_flag = false;
                        this.calendar_flag = true;
                        this.getMonths();
                    }
                },
                open_applications() {
                    if(this.applications_flag === false) {
                        this.message_edit_application = '';
                        this.categories_flag = false;
                        this.qualifications_flag = false;
                        this.masters_flag = false;
                        this.admins_flag = false;
                        this.services_flag = false;
                        this.calendar_flag = false;
                        this.applications_flag = true;
                    }
                },


                add_qualification() {
                    this.service_qualifications.push(['']);
                },
                delete_qualification(index) {
                    this.service_qualifications.splice(index, 1);
                },
                add_master(index) {
                    this.service_qualifications[index].push('');
                },
                delete_master(index, index2) {
                    this.service_qualifications[index].splice(index2, 1);
                },


                add_qualification_edit() {
                    this.service_obj_edit.qualifications.push({qualification_id: 0, masters: [{id: 0}]});
                },
                delete_qualification_edit(index) {
                    this.service_obj_edit.qualifications.splice(index, 1);
                },
                add_master_edit(index) {
                    this.service_obj_edit.qualifications[index].masters.push({id: 0});
                },
                delete_master_edit(index, index2) {
                    this.service_obj_edit.qualifications[index].masters.splice(index2, 1);
                },


                getInfoMonth() {
                    if ((this.month_id != 0) && (this.master_id != 0)) {
                        if(this.focus_btn_id !== 0) {
                            document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus');
                            this.focus_btn_id = 0;
                        }
                        this.calendar = '';
                        this.date_times = [''];
                        this.times = [''];
                        this.selected_mouth = this.months.find(month => `${month.number_month}_${month.year}` === this.month_id);
                        this.calendar = this.calendars.find(calendar => calendar.master_id === this.master_id &&
                            calendar.month_number === this.selected_mouth.number_month &&
                            calendar.year === this.selected_mouth.year);
                        if (!this.calendar) {
                            this.focus_btn_id = 0;
                            this.calendar = '';
                            this.date_times = [''];
                        }
                    } else {
                        this.focus_btn_id = 0;
                        this.calendar = '';
                        this.date_times = [''];
                    }
                },
                getFocusBtn(id) {
                    if(this.focus_btn_id !== 0) {
                        document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus');
                    }
                    this.focus_btn_id = id;
                    document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus');
                    this.getDateTimes(id);
                },
                add_time() {
                    this.times.push('');
                },
                delete_time(time_index) {
                    this.times.splice(time_index, 1);
                },
                add_time_edit() {
                    this.date_times.push('');
                },
                delete_time_edit(time_index) {
                    this.date_times.splice(time_index, 1);
                },
                btnColor(day) {
                    if (this.calendar !== '' && this.calendar) {
                        for (let i = 0; i < this.calendar.dates.length; i++) {
                            let date = this.calendar.dates[i];

                            if (date.hasOwnProperty(day)) return true;
                        }
                    }
                    return false;
                },
                btnHidden(day) {
                    let today = new Date();
                    let date = new Date(this.selected_mouth.year + '-' + this.selected_mouth.number_month + '-' + day);
                    if (today.getTime() > date.getTime()) return true
                },
                getDateTimes(day) {
                    if (this.calendar && this.calendar != '' && this.calendar.length !== 0) {
                        for (let i = 0; i < this.calendar.dates.length; i++) {
                            let date = this.calendar.dates[i];

                            if (date.hasOwnProperty(day)) {
                                this.date_times = date[day];
                                this.date_times = this.date_times.filter(item => item[1] === 'Свободно');
                                if(this.date_times.length === 0) {
                                    this.date_times = [''];
                                }
                                break;
                            } else {
                                this.date_times = [''];
                            }
                        }
                    }
                },
                async getCalendarDay() {
                    const response = await fetch('{{route('getCalendars')}}');
                    this.calendars = await response.json();
                    this.calendars = this.calendars.map(calendar => {
                        calendar.dates.sort((date1,date2) => Number(Object.keys(date1)[0]) - Number(Object.keys(date2)[0]));
                        return calendar;
                    });
                    this.calendar = this.calendars.find(calendar => calendar.master_id === this.master_id &&
                        calendar.month_number === this.selected_mouth.number_month &&
                        calendar.year === this.selected_mouth.year);
                    this.getDateTimes(this.focus_btn_id);
                    document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus')
                    document.getElementById(this.focus_btn_id).classList.add('btn-calendar-date-yes');
                    this.focus_btn_id = 0;
                },


                date_format(date) {
                    let day = new Date(date).getDate();
                    let month = new Date(date).getMonth() + 1;
                    let year = new Date(date).getFullYear();
                    if (day < 10) {
                        day = '0' + day;
                    }

                    if (month < 10) {
                        month = '0' + month;
                    }
                    return `${day}.${month}.${year}`;
                },
                compare_dates(date, time) {
                    let day = new Date(date).getDate();
                    let month = new Date(date).getMonth();
                    let year = new Date(date).getFullYear();
                    let new_day = new Date(year, month, day, time.split(':')[0], time.split(':')[1]).getTime();
                    let today = new Date().getTime();
                    if (new_day - today > 0) return true;
                },


                //pagination
                category_pagination() {
                    if (this.category_open_short === false) this.category_open_short = true;
                    else this.category_open_short = false;
                },
                qualification_pagination() {
                    if (this.qualification_open_short === false) this.qualification_open_short = true;
                    else this.qualification_open_short = false;
                },
                master_pagination() {
                    if (this.master_open_short === false) this.master_open_short = true;
                    else this.master_open_short = false;
                },
                admin_pagination() {
                    if (this.admin_open_short === false) this.admin_open_short = true;
                    else this.admin_open_short = false;
                },
                service_pagination() {
                    if (this.service_open_short === false) this.service_open_short = true;
                    else this.service_open_short = false;
                },
                calendar_pagination() {
                    if (this.calendar_open_short === false) this.calendar_open_short = true;
                    else this.calendar_open_short = false;
                },
                application_pagination() {
                    if (this.application_open_short === false) this.application_open_short = true;
                    else this.application_open_short = false;
                },

            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });
            },

            computed: {
                filteredCategories() {
                    return this.search_category ? this.categories.filter(category =>
                        category.title.toLowerCase().includes(this.search_category.toLowerCase())) : this.categories;
                },
                filteredQualifications() {
                    return this.search_qualification ? this.qualifications.filter(category =>
                        category.title.toLowerCase().includes(this.search_qualification.toLowerCase())) : this.qualifications;
                },
                filteredMasters() {
                    // Поиск
                    let filtered = this.search_master ? this.masters.filter(master =>
                        master.name.toLowerCase().includes(this.search_master.toLowerCase()) ||
                        master.specialization.toLowerCase().includes(this.search_master.toLowerCase())) : this.masters;

                    // Фильтрация
                    return this.master_filter_id != 0
                        ? filtered.filter(master => master.qualification_id == this.master_filter_id) : filtered;
                },
                filteredAdmins() {
                    return this.search_admin ? this.admins.filter(admin =>
                        admin.name.toLowerCase().includes(this.search_admin.toLowerCase()) ||
                        admin.surname.toLowerCase().includes(this.search_admin.toLowerCase())) : this.admins;
                },
                filteredServices() {
                    // Поиск
                    let filtered = this.search_service ? this.services.filter(service =>
                        service.title.toLowerCase().includes(this.search_service.toLowerCase())) : this.services;

                    // Фильтрация
                    return this.service_filter_id != 0
                        ? filtered.filter(master => master.category_id == this.service_filter_id) : filtered;
                },
                filteredCalendars() {
                    // Поиск
                    let filtered = this.search_calendar ? this.calendars.filter(calendar =>
                        calendar.master_name.toLowerCase().includes(this.search_calendar.toLowerCase())) : this.calendars;

                    // Фильтрация
                    return this.calendar_filter_id != 0
                        ? filtered.filter(calendar => calendar.month_name == this.calendar_filter_id) : filtered;
                },
                filterApplications() {
                    return this.applications_status_filter_id != 0
                        ? this.applications.filter(application => application.status == this.applications_status_filter_id)
                        : this.applications;
                },
            },

            mounted() {
                this.getCategories();
                this.getQualifications();
                this.getMasters();
                this.getUsers();
                this.getAdmins();
                this.getServices();
                this.getCalendars();
                this.getApplications();

            }

        }
        Vue.createApp(App).mount('#Admin');
    </script>
@endsection
