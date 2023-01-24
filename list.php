
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WDS Admin Panel - Manage Data</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-storage.js"></script>
    <script type="module">
        var status = 0;
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-analytics.js";

        
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries
      
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
          apiKey: "AIzaSyCSnT9lLEpvNWfLmRsmCbzuSLyQCdZy_Wo",
          authDomain: "fluttergame-c8b05.firebaseapp.com",
          databaseURL: "https://fluttergame-c8b05-default-rtdb.firebaseio.com",
          projectId: "fluttergame-c8b05",
          storageBucket: "fluttergame-c8b05.appspot.com",
          messagingSenderId: "319212975804",
          appId: "1:319212975804:web:23ca7e9fb83d20ceb582b9",
          measurementId: "G-BRN4CLCDBZ"
        };
      
        // Initialize Firebase
        // firebase.initializeApp(firebaseConfig);
        // console.log(firebase);
        // const app = firebase.initializeApp(firebaseConfig);
        const app = initializeApp(firebaseConfig);
        firebase.initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);

        import { getFirestore, doc, getDoc, getDocs, setDoc, collection, addDoc, updateDoc, deleteDoc, deleteField}
        from "https://www.gstatic.com/firebasejs/9.8.4/firebase-firestore.js"

        const db = getFirestore();

        // Add New Item
        async function add_Item(){
            
            var ref = doc(db,'words', txtID.value);
            var storage = firebase.storage();
            //  var storage = getFirestore();
            //  var file=document.getElementById("flNumberPlate").files[0];
            var ref2=storage.ref();
            //  var thisref=storageref.child(type).child(file.name).put(file);
            //  console.log(47)
            // console.log(thisref);
           // const ref = firebase.storage().ref();
            const file = document.querySelector("#flNumberPlate").files[0];
            const name = +new Date() + "-" + file.name;
            const metadata = {
                contentType: file.type
            };
            // const task = ref2.child(name).put(file, metadata);
            var url_response = ''
            const task = ref2.child(name).put(file, metadata);
            task
                .then(snapshot => snapshot.ref.getDownloadURL())
                .then(url => {
                  url_response = url 
                  const docRef = setDoc(
                        ref, {
                            
                            country:txtCountryName.value,
                            country_code: txtCountryCode.value,
                            facts:txtFact.value,
                            isVisible:chkStatus.checked,
                            letter: url_response
                    }).then(()=>{
                        getAllDataOnce;
                        alert("Data SAVED successfully!");
                    }).catch((error)=> {
                        alert("Error SAVING in data: "+ error);
                    })

              
                // display_msg(txtCountryName.value,txtCountryCode.value,txtFact.value,chkStatus.checked,url_response)
                document.querySelector("#imgNumberPlate").src = url;
                document.querySelector("#img_name").src = url;
                })
                .catch(console.error);
                // console.log(typeof url_response)
                // console.log(url_response)
                // console.log('img_name')
                // console.log( $('#img_name').val())
          
            // const docRef = await setDoc(
            //     ref, {
            //         country:txtCountryName.value,
            //         country_code: txtCountryCode.value,
            //         facts:txtFact.value,
            //         isVisible:chkStatus.checked,
            //         letter: url_response
            // }).then(()=>{
            //     getAllDataOnce;
            //     alert("Data SAVED successfully!");
            // }).catch((error)=> {
            //     alert("Error SAVING in data: "+ error);
            // })
         //  console.log(docRef)
            return false;
        }

        // function display_msg(txtCountryName,txtCountryCode,txtFact,chkStatus,url_response){
        //     if(status == 1){
        //         console.log(117)
        //     console.log(txtCountryName)
        //     const docRef = await setDoc(
        //         ref, {
        //             country:txtCountryName,
        //             country_code: txtCountryCode,
        //             facts:txtFact,
        //             isVisible:chkStatus,
        //             letter: url_response
        //     }).then(()=>{
        //         getAllDataOnce;
        //         alert("Data SAVED successfully!");
        //     }).catch((error)=> {
        //         alert("Error SAVING in data: "+ error);
        //     })
        //  //   console.log(docRef)
        //     return false;
        //     // return false;
        //     }
        // }

        btnSubmit.addEventListener("click", add_Item);
        async function getCurDoc(id){
            var ref = doc(db,'words', id);   
            const docSnap = await getDoc(ref);
            console.log(docSnap);
            if(docSnap.exists){
                txtID.value = docSnap.id;
                txtCountryName.value = docSnap.data().country;
                txtCountryCode.value = docSnap.data().country_code;
                txtFact.value = docSnap.data().facts;
                chkStatus.checked = docSnap.data().isVisible;
                imgNumberPlate.src = docSnap.data().letter;
            } else {
                alert("No such inforamtion found!")
            }
        }

        //btnLoad.addEventListener("click", getCurDoc);
       

        async function deleteDocument(id){
            var ref = doc(db,'words', id);   
            const docSnap = await getDoc(ref);

            if(!confirm("Are you sure?\nYou want to delete this information\nthis process is irrecoverable.")) return;

            if(!docSnap.exists){
                alert("Document doesn't exist!"); 
                return;   
            } 
            await deleteDoc(ref)
            .then(()=>{
                getAllDataOnce;
                alert("Document DELETED successfully!");
            }).catch((error)=>{
                alert("Error deleting data: "+error);
            })           
        }

        //btnDelete.addEventListener("click", deleteDocument);

        var tDocsBody = document.getElementById("tDocsBody");
        function addItemToTable(id,country,country_code,facts, isVisible,letter){
            let tRow = document.createElement("tr");
            let tdID = document.createElement("td");
            tdID.innerHTML = id;
            tRow.appendChild(tdID);
            let tdImg = document.createElement("td");
            let imgNumberPlate = document.createElement("img");
            imgNumberPlate.width="100"
            imgNumberPlate.src=letter;
            tdImg.appendChild(imgNumberPlate)
            tRow.appendChild(tdImg);
            let tdInfo = document.createElement("td");
                let spCountryCode = document.createElement("span");
                spCountryCode.innerHTML=country_code;
                tdInfo.appendChild(spCountryCode)
                let spCountryName = document.createElement("span");
                spCountryName.innerHTML=country;
                tdInfo.appendChild(spCountryName);
                let spFacts = document.createElement("span");
                spFacts.innerHTML=facts;
                tdInfo.appendChild(spFacts)
                let spIsVisible = document.createElement("span");
                spIsVisible.innerHTML=isVisible;
                tdInfo.appendChild(spIsVisible);
                tRow.appendChild(tdInfo);
            let tdAction = document.createElement("td");
                let btnEdit = document.createElement("button");
                    btnEdit.innerText="EDIT";
                    btnEdit.addEventListener("click", getCurDoc.bind(this,id));
                    tdAction.appendChild(btnEdit);
                let btnDelete = document.createElement("button");
                    btnDelete.addEventListener("click", deleteDocument.bind(this,id));
                    btnDelete.innerText="DELETE"
                    tdAction.appendChild(btnDelete);
                    tRow.appendChild(tdAction)
                    tDocsBody.appendChild(tRow);
          
        };
        //Test Item
        //addItemToTable(1,"INdia","IND","Just Test",false,'https://th.bing.com/th/id/OIP.dUan89y1CAw6PpnJusdP8gHaEK?w=286&h=180&c=7&r=0&o=5&pid=1.7')
        function AddAllItemsToTheTable(allWords){
            //tbody = document.getElementById("");
            tDocsBody.innerHTML="";
            allWords.forEach(itemElement => {
                addItemToTable(itemElement.id,
                    itemElement.data().country,
                    itemElement.data().country_code,
                    itemElement.data().facts,
                    itemElement.data().isVisible,
                    itemElement.data().letter
                    )
            });
        };

        async function getAllDataOnce(){
            const querySnapshot = await getDocs(collection(db, "words"));
            var allDocs = [];
                querySnapshot.forEach((doc) => {
                // doc.data() is never undefined for query doc snapshots
                allDocs.push(doc);
                //console.log(doc.id, " => ", doc.data());
                });
                AddAllItemsToTheTable(allDocs);
        };

        window.onload = getAllDataOnce;
      </script>

</head>
<body>
    <h2>WDS - Admin</h2>

        <label>ID</label><input type="text" id="txtID" /><br/>
        <label>Country</label><input type="text" id="txtCountryName" /><br/>
        <label>Code</label><input type="text" id="txtCountryCode"/><br/>
        <label>Fact</label><input type="text" id="txtFact"/><br/>
        <label>Status</label><input type="checkbox" id="chkStatus" checked/><br/>
        <label>Number Plate</label><input type="file" id="flNumberPlate" name="flNumberPlate"><br/>
        <img id="imgNumberPlate"/>
        <inout type="hidden" id="img_name" name="img_name"/>
        <br/><br/>
        <button id="btnSubmit">SAVE</button>
         <!-- <button id="btnLoad">LOAD</button> <button id="btnDelete">DELETE</button> -->
    
    <hr/>
    <div>
        <h3>List</h3>
        <table id="tblData" border="1" collapse="true" >
            <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Details</th>
                <th>Actions</th>
            </tr>
        </thead>
            <tbody id="tDocsBody">

            </tbody>
        </table>
    </div>
</body>
</html>