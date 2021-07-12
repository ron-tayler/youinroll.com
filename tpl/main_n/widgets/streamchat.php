
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


<link href="/chatik/105/static/css/main.b90a7004.chunk.css" rel="stylesheet">


<style>

.chat-wrapper {
   height: 100vh;
}

</style>



<div class="chat-wrapper">
<div id="chatik"></div>
    <div class="chat-area" style="display: none;">

<script>
  window.streamimg="/<?=$chatImage?>";
  console.log(window.streamimg)
</script>


             <!--   <img class="chat-area-profile" src="<?=$chatImage?>" alt="" /> -->

        <div class="chat-area-footer">
            <div id="openfile" style="
                position: relative;
                display: contents;
            ">
            </div>
        </div>
    </div>
</div>
















<script>!function(e){function r(r){for(var n,a,l=r[0],i=r[1],c=r[2],p=0,s=[];p<l.length;p++)a=l[p],Object.prototype.hasOwnProperty.call(o,a)&&o[a]&&s.push(o[a][0]),o[a]=0;for(n in i)Object.prototype.hasOwnProperty.call(i,n)&&(e[n]=i[n]);for(f&&f(r);s.length;)s.shift()();return u.push.apply(u,c||[]),t()}function t(){for(var e,r=0;r<u.length;r++){for(var t=u[r],n=!0,l=1;l<t.length;l++){var i=t[l];0!==o[i]&&(n=!1)}n&&(u.splice(r--,1),e=a(a.s=t[0]))}return e}var n={},o={1:0},u=[];function a(r){if(n[r])return n[r].exports;var t=n[r]={i:r,l:!1,exports:{}};return e[r].call(t.exports,t,t.exports,a),t.l=!0,t.exports}a.m=e,a.c=n,a.d=function(e,r,t){a.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:t})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,r){if(1&r&&(e=a(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(a.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var n in e)a.d(t,n,function(r){return e[r]}.bind(null,n));return t},a.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(r,"a",r),r},a.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},a.p="/chatik/105/";var l=this.webpackJsonpreact=this.webpackJsonpreact||[],i=l.push.bind(l);l.push=r,l=l.slice();for(var c=0;c<l.length;c++)r(l[c]);var f=i;t()}([])</script>






<script src="/chatik/105/static/js/2.4831c238.chunk.js"></script>


<script src="/chatik/105/static/js/main.ea0769eb.chunk.js"></script>
