
<script>

window.onload = function() {
  init();


const targetNode = document.getElementById('ShareModal');
const config = { attributes: true, childList: true, subtree: true };

const callback = function(mutationsList, observer) {
    // Use traditional 'for loops' for IE 11
    for(const mutation of mutationsList) {
        if (mutation.type === 'childList') {
            console.log('A child node has been added or removed.');
        }
        else if (mutation.type === 'attributes') {
            console.log('The ' + mutation.attributeName + ' attribute was modified.');
        }
    }
};



// Create an observer instance linked to the callback function
const observer = new MutationObserver(callback);

// Start observing the target node for configured mutations
observer.observe(targetNode, config);

// Later, you can stop observing
observer.disconnect();

};

function addClassNameListener(elemId, callback) {
	console.log("fired");
    var elem = document.getElementById(elemId);
    var lastClassName = elem.className;
    window.setInterval( function() {
       var className = elem.className;
        if (className !== lastClassName) {
            callback();
            lastClassName = className;
        }
    },10);
}




function myFunction() {
	var popup = document.getElementById("report-author");

  //document.getElementById("stream-content").style.zIndex = "235";

  document.getElementById("stream-content").style.top = "61px";
}

addClassNameListener('ShareModal', myFunction);


</script>









<div class="chat-wrapper">
    <div class="chat-area">
        <div class="chat-area-header">
            <div class="chat-area-title">ЧАТ ТРАНСЛЯЦИИ  <?/*=$chatTitle*/?></div>




                                <div class="inter-icons">



                                    <div class="likes-bar">
    				       <div class="aaa">
                                            <a role="button" onClick="myFunction()"  data-toggle="modal" href="#ShareModal"
                                                title=" <?php echo _lang('Share or Embed');?>">
                                                <!-- <i class="material-icons ico-flipped">&#xE15E;</i> -->
                                                <img src="/tpl/main/images/share.svg" class="share" />
                                                <span class="hidden-xs">
                                                    <?php
                                                        //echo _lang('Share');
                                                        echo 'Поделиться';
                                                        ?>
                                                </span>
                                            </a>
		                       </div>
                                    </div>

                                </div>







            <div class="chat-area-group">
                <img class="chat-area-profile" src="<?=$chatImage?>" alt="" />
                <!-- <img class="chat-area-profile"
                    src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3364143/download+%282%29.png" alt="">
                <img class="chat-area-profile"
                    src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3364143/download+%2812%29.png" alt="" />
                    -->
            </div>
        </div>
        <div class="chat-area-main">

        </div>
        <div class="chat-area-footer">
            <?if(true ){?>
	    <!-- user_group() !== 4 && user_group() !== 5 && user_group() !== 3 && user_group() !== 8-->
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
            <svg id="smiles" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile">
                <circle cx="12" cy="12" r="10" />
                <path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01" />
            </svg>
            <?include(TPL.'/widgets/smiles.php');?>
            <input type="file" id='messageFile' style="display:none;" />
            <input type="text" id='messageInput' placeholder="Сообщение..." />
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-send">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
            <?}?>
        </div>
    </div>
</div>
