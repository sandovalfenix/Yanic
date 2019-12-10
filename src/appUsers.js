firebase.initializeApp({
    apiKey: 'AIzaSyCQmMBRu8LqK9U5fv9jPmLgLruHNm92qpQ',
    authDomain: 'yanic-25102.firebaseapp.com',
    projectId: 'yanic-25102'
});

// Initialize Cloud Firestore through Firebase
var db = firebase.firestore();
const app = new Vue({
    el: "#app",
    data:{
        Users: [],
        User: [],
    },
    methods: {
        create(){
            var User = Object.assign({},this.User);
            db.collection("Users").add(User)
            .then(function (docRef) {
                console.log("Registro añadido con ID: ", docRef.id);
            })
            .catch(function (error) {
                console.error("Error al agregar registro: ", error);
            });                
            this.User = []; 
        },
        home(id){
            var docRef = db.collection("Users").doc(id);
            this.User = [];
            docRef.get().then(function(doc) {
                if (doc.exists) {
                    console.log("Document data:", doc.data());
                    var data = doc.data();
                    data.id = id;
                    app._data.User = data;
                } else {
                    // doc.data() will be undefined in this case
                    console.log("No such document!");
                }
            }).catch(function(error) {
                console.log("Error getting document:", error);
            });
        },            
        deleted(id){
            db.collection("Users").doc(id).delete().then(function() {
                console.log("Document successfully deleted!");
            }).catch(function(error) {
                console.error("Error removing document: ", error);
            });
        },
        edit(id){
            this.home(id);
        },
        update(id){                
            var docRef = db.collection("Users").doc(id).update(this.User)
            .then(function() {
                console.log("Actualizacion Completada");
                app._data.User = [];
            }).catch(function(error) {
                console.log("Error al actualizar documento:", error);
            });
        }
    },
    delimiters: ['([', '])'],
});

db.collection("Users").orderBy('username').onSnapshot(function(querySnapshot) {
    app._data.Users = [];
    querySnapshot.forEach(function(doc) {
        // doc.data() is never undefined for query doc snapshots
        var data = doc.data();
        data.id = doc.id;
        app._data.Users.push(data);
    });        
});

/*var a = location.pathname.substring(1).split("/");
console.log(a);*/