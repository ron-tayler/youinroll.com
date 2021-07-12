<?php ?>
<link rel="stylesheet" href="/tpl/main_rtf/css/carousel.css?v=<?=filemtime(DIR_SITE.'/tpl/main_rtf/css/carousel.css')?>">
<link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
<div class="stream-carousel" v-cloak>
    <stream-carousel inline-template>
        <div class="carousel">
            <div class="carousel-btn carousel-btn-left" @click="changeVideo('right')">
                <i class="material-icons">&#xe5cb;</i>
            </div>
            <div class="carousel-videos">
                <div class="carousel-video-box" v-for="video in videos" :class="video.position">
                    <div class="carousel-videojs-box" v-if="video.position==='main'">
                        <video-js :video_src="video.stream_url"></video-js>
                    </div>
                    <div class="carousel-desc-box" v-if="video.position==='main'">
                        <div class="carousel-desc-name">
                            <div class="carousel-desc-user-img-box">
                                <img :src="video.user_img" alt="">
                            </div>
                            <div class="carousel-desc-span">
                                <div class="carousel-desc-span-user-name">
                                    <a :href="video.user_url">{{video.user_name}}</a>
                                </div>
                                <!--
                                <div class="carousel-desc-span-group">
                                    ...
                                </div>

                                <div class="carousel-desc-span-views">
                                    {{video.stream_views}} просмотров
                                </div>
                                -->
                            </div>
                        </div>
                        <div class="carousel-desc-stream-name">
                            <a :href="video.stream_page">{{video.stream_name}}</a>
                        </div>
                        <div class="carousel-desc-tags">
                            <div class="carousel-desc-tag" v-for="tag in video.stream_tags">
                                {{tag}}
                            </div>
                        </div>
                        <div class="carousel-desc-desc">
                            {{video.stream_desc}}
                        </div>
                    </div>
                    <img :src="video.stream_img" alt="" v-else />
                </div>
            </div>
            <div class="carousel-btn carousel-btn-right" @click="changeVideo('left')">
                <i class="material-icons carousel__chevron">&#xe5cc;</i>
            </div>
        </div>
    </stream-carousel>
</div>
<script type="module" src="/tpl/main_rtf/js/carousel.js"></script>
<script src="/tpl/main_n/styles/js/modules/videojs.min.js"></script>
