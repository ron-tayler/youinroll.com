import Vue from '/tpl/main_n/styles/js/vue.js';

$(document).on('ready', function(){
    if ($(window).width() > 1000) {
        Vue.component("video-carousel", {
            data: function () {
                return {
                    currentVideo: 0, //refers to tertiary video on leftmost; main video with be currentVideo+2
                    totalVideos: 8,
                    videos: [],
                    videoPositions: {
                        0: "tertiary tertiary-left",
                        1: "secondary secondary-left",
                        2: "main",
                        3: "secondary secondary-right",
                        4: "tertiary tertiary-right"
                    }
                };
            },
            computed: {},
            methods: {
                changeVideo: function (direction, position) {
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
                        if (newIndex > 4) {
                            newIndex %= 5;
                        }
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
                    console.log("in conferences");
                    console.log(conferences.length);
                    if(conferences.length === 0)
                    {
                        $('#confSlider').remove()
                    };
                    if(conferences.length === 5)
                    {
                        for (let i = 0; i < conferences.length; i++) {

                            let videoId = conferences[i].id;
                            let videoEmbed = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
                            let videoImage = conferences[i].cover;
                            let channelImage = conferences[i].cover;
                            let video = {
                                id: i,
                                url: conferences[i].url,
                                thumbnail: videoImage,
                                isOnAir: (conferences[i].on_air === '1') ? true : false,
                                isPlanned: (conferences[i].started_at === new Date().toISOString().slice(0, 19).replace('T', ' ') ) ? true : false,
                                embed: videoEmbed,
                                description: conferences[i].description,
                                position: videoPositions[i],
                                videoId: videoId,
                                active: false,
                                channelImage: conferences[i].authorImage,
                                channelName: conferences[i].author,
                                channelTitle: conferences[i].name,
                                channelViews: conferences[i].views
                            };

                            videos.push(video);
                        }
                    }
                });
            }
        });

        let app = new Vue({
            el: ".categories-header",
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