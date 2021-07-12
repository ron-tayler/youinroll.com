import Vue from '/tpl/main_n/styles/js/vue.js';

$(window).on('load', function(){
    if ($(window).width() > 1000) {
        Vue.component("stream-carousel", {
            data: ()=> ({
                currentVideo: 0, //refers to tertiary video on leftmost; main video with be currentVideo+2
                totalVideos: 5,
                videos: [],
                videoPositions: {
                    0: "tertiary tertiary-left",
                    1: "secondary secondary-left",
                    2: "main",
                    3: "secondary secondary-right",
                    4: "tertiary tertiary-right"
                }
            }),
            computed: {},
            methods: {
                changeVideo(direction, position) {
                    if (direction == "left") {
                        this.currentVideo--;
                        if (this.currentVideo < 0) {
                            this.currentVideo = 4;
                        }
                    } else if (direction == "right") {
                        this.currentVideo++;
                        if (this.currentVideo > 4) {
                            this.currentVideo = 0;
                        }
                    } else {
                        switch (position) {
                            case "secondary secondary-right":
                                this.changeVideo("left");
                                break;
                            case "secondary secondary-left":
                                this.changeVideo("right");
                                break;
                            case "tertiary tertiary-right":
                                this.changeVideo("left");
                                this.changeVideo("left");
                                break;
                            case "tertiary tertiary-left":
                                this.changeVideo("right");
                                this.changeVideo("right");
                                break;
                        }
                    }

                    this.videos.forEach((video, i) => {
                        video.active = false;
                        let newIndex = i + this.currentVideo;
                        if (newIndex > 4) newIndex %= 5;
                        if (newIndex===3) video.active = true;
                        video.position = this.videoPositions[newIndex];
                    });
                }
            },
            mounted: function () {
                //Generate array of fake carousel videos

                let videoPositions = this.videoPositions;
                let videos = this.videos;

                $.get('https://youinroll.com/lib/ajax/getActiveConferences.php', function (data){

                    let conferences = JSON.parse(data);
                    console.log("in conferences:",conferences.length);
                    if(conferences.length === 0)
                    {
                        $('#confSlider').remove()
                    };
                    if(conferences.length > 2)
                    {
                        for (let i = 0; i < conferences.length; i++) {
                            let conf = conferences[i];
                            let video = {
                                index: i,
                                stream_page: conf.url,
                                stream_img: conf.cover,
                                stream_status: (conf.status === 'on_air'),
                                stream_url: conf.stream_url,
                                stream_name: conf.name,
                                stream_tags: conf.tags,
                                stream_desc: conf.description,
                                stream_views: conf.views,
                                user_url: conf.user_url,
                                user_img: 'https://youinroll.com/'+conf.userImage,
                                user_name: conf.user_name,
                                position: videoPositions[i]
                            };
                            videos.push(video);
                        }
                    }
                });
            },
        });

        Vue.component("video-js", {
            data: ()=>({
                player: null
            }),
            props: {
                video_src: String,
                options: {
                    type: Object,
                    default: ()=> ({
                        autoplay: true,
                        controls: true,
                        poster: 'https://youinroll.com/storage/uploads/def-stream.jpg'
                    })
                }
            },
            template: `<video id="carousel-video-js" class="video-js"><source v-bind:src="video_src"></video>`,
            mounted() {
                this.player = videojs('carousel-video-js', this.options, function onPlayerReady() {
                    console.log('onPlayerReady', this);
                    setTimeout(function(){
                        videojs('carousel-video-js').play();
                    },1000);
                })
            },
            beforeDestroy() {
                if (this.player) {
                    this.player.dispose()
                }
            }
        });

        let app = new Vue({
            el: ".stream-carousel",
            data: {
                users: []
            },
            methods: {},
            mounted: function () {
                let self = this;
            }
        });
    }
});