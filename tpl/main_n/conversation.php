<?include(TPL.'/modals/addChatParticipant.php');?>
<div class="app" id='chatMainBlock' data-active="<?=token_id()?>">
    <div class="chat-wrapper">
        <div class="conversation-area">
            <div class="search-bar">
                <input type="text" placeholder="Поиск..." />
            </div>
            <?/*?><button class="add"></button><?*/?>
            <div class="overlay"></div>
        </div>
        <div class="chat-area">
            <div class="chat-area-header">
                <div class="chat-area-title">Выберите чат</div>
                <div class="chat-area-group">
                    <span data-toggle="modal" data-target="#addParticipantModal" id="addParticipant">+</span>
                </div>
            </div>
            <div class="chat-area-main">

            </div>
            <div class="chat-area-footer">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video">
                    <path d="M23 7l-7 5 7 5V7z" />
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <path d="M21 15l-5-5L5 21" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-plus-circle">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v8M8 12h8" />
                </svg> -->
                <div id="openfile" style="
                    position: relative;
                    display: contents;
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip">
                        <path
                            d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                    </svg>
                </div>
                <input type="file" id='messageFile' style="display:none;" />
                <input type="text" id='messageInput' placeholder="ОТПРАВКА ЕЩЁ В РАЗРАБОТКЕ..." />
                <svg id="smiles" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01" />
                </svg>
                <?include(TPL.'/widgets/smiles.php');?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-send">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </div>
        </div>
        <div class="detail-area">
            <div class="detail-area-header">
                <div class="msg-profile group">
                    <img class='chat-area-profile' src="">
                </div>
                <div class="detail-title">Выберите чат</div>
                <!-- <div class="detail-subtitle">Created by Aysenur, 1 May 2020</div> -->
                <div class="detail-buttons dropdown">
                    <button class="detail-button dropdown-toggle" id='addToConv' data-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-plus">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Добавить в чат
                    </button>
                    <ul class="dropdown-menu" id='confBlock' aria-labelledby="addToConv">
                        
                    </ul>
                    <a href="" class="detail-button" id='writeTo'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-message-square">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Написать
                    </a>
                </div>
            </div>
            <!-- <div class="detail-changes">
                <input type="text" placeholder="Search in Conversation">
                <div class="detail-change">
                    Change Color
                    <div class="colors">
                        <div class="color blue selected" data-color="blue"></div>
                        <div class="color purple" data-color="purple"></div>
                        <div class="color green" data-color="green"></div>
                        <div class="color orange" data-color="orange"></div>
                    </div>
                </div>
                <div class="detail-change">
                    Change Emoji
                    <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-thumbs-up">
                        <path
                            d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3" />
                    </svg>
                </div>
            </div>
            <div class="detail-photos">
                <div class="detail-photo-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <path d="M21 15l-5-5L5 21" />
                    </svg>
                    Shared photos
                </div>
                <div class="detail-photo-grid">
                    <img
                        src="https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2168&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1516085216930-c93a002a8b01?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2250&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1458819714733-e5ab3d536722?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=933&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1520013817300-1f4c1cb245ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2287&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1494438639946-1ebd1d20bf85?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2247&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1559181567-c3190ca9959b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1300&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1560393464-5c69a73c5770?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1301&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1506619216599-9d16d0903dfd?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2249&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1481349518771-20055b2a7b24?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2309&q=80" />

                    <img
                        src="https://images.unsplash.com/photo-1473170611423-22489201d919?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2251&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1579613832111-ac7dfcc7723f?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2250&q=80" />
                    <img
                        src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2189&q=80" />
                </div>
                <div class="view-more">View More</div>
            </div> -->
            <!-- <a href="https://twitter.com/AysnrTrkk" class="follow-me" target="_blank">
                <span class="follow-text">
                    <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                        stroke-linejoin="round" class="css-i6dzq1">
                        <path
                            d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                        </path>
                    </svg>
                    Follow me on Twitter
                </span>
                <span class="developer">
                    <img src="https://pbs.twimg.com/profile_images/1253782473953157124/x56UURmt_400x400.jpg" />
                    Aysenur Turk — @AysnrTrkk
                </span>
            </a> -->
        </div>
    </div>
</div>