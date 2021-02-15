import Vue from '/tpl/main_n/styles/js/vue.js';

// https://static-cdn.jtvnw.net/ttv-boxart/Super%20Smash%20Bros.%20Ultimate-188x250.jpg
let videoIds = [
	"sG3BomZqem4",
	"V0TyHhXkjsE",
	"nUVRTWWb-8I",
	"6xz_dbmYc1g",
	"s3fdI6dw1Tk"
];

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
        
        $.get('/lib/ajax/getActiveConferences.php', function (data){

            let conferences = JSON.parse(data);

            for (let i = 0; i < conferences.length; i++) {

                let videoId = conferences[i].id;
                let videoEmbed = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
                let videoImage = conferences[i].cover;
                let channelImage = conferences[i].cover;
                let video = {
                    id: i,
                    url: conferences[i].url,
                    thumbnail: videoImage,
                    isPlanned: (conferences[i].on_air === '1') ? false : true,
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
        });
	}
});

let app = new Vue({
	el: "#home-content",
	data: {
		users: []
	},
	methods: {},
	mounted: function () {
		let self = this;
	}
});
