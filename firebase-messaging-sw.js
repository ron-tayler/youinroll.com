importScripts("https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.6.8/firebase-messaging.js");


var firebaseConfig = {
    apiKey: "AIzaSyDuozD3WtKHl4YxXnqFNhKyAmnHkFvJphM",
    authDomain: "youinrol.firebaseapp.com",
    projectId: "youinrol",
    storageBucket: "youinrol.appspot.com",
    messagingSenderId: "477697040270",
    appId: "1:477697040270:web:f944c28e376a88777d0edf"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);  


const messaging = firebase.messaging();