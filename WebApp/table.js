var requestObj = new XMLHttpRequest();
var firstFeedbackMessage = true;
lookUpUser();

var isMouseDown = false, isSelected;
var currentCol;
$("#schedule td").mousedown(mouseHold).mouseover(mouseDrag);
$(document)
    .mouseup(function () {
        isMouseDown = false;
});

$('#submit_time').on('submit', function(e) {
    document.getElementById('data').value = JSON.stringify(processSelected());    
});

function lookUpUser() {	
    var scriptURL = "fakeDb.php";
                
    ///* adding name to url */
    //var name = document.getElementById("name").value;
    //scriptURL += "?name=" + name;
    ///* adding random value to url to avoid cache */
    //var randomValueToAvoidCache = (new Date()).getTime();
    //scriptURL += "&randomValue=" + randomValueToAvoidCache;
                
    var asynch = true; // asynchronous
    requestObj.open("GET", scriptURL, asynch);
    /* setting the function that takes care of the request */
    requestObj.onreadystatechange = processProgress;
    /* sending request */
    requestObj.send(null);
}
		    
function processProgress() {
    if (requestObj.readyState === 4) {
        if (requestObj.status === 200) {
            /* retrieving response */
            var results = requestObj.responseText;
                    
            /* parsing string response */
            var arrayWithResults = JSON.parse(results);
            occupiedTime(arrayWithResults, true);
            occupiedTime(arrayWithResults, false);

        } else {
            alert("Request Failed.");
        }
    }
}
    
function occupiedTime(obj, toggle) {
    var time = toggle? obj.mytime: obj.othertime;
    for(var i of time) {
        var day = i.day;
        var start = i.start;
        var end = i.end;
        
        for(var idx = start; idx <= end; idx++) {
            colorSlot(day, idx, toggle);
        }
    }
}

function colorSlot(col, row, toggle) {
    var color = toggle? 'selected':'otherselected';
    $("#schedule [data-col='" + col + "'][data-row='" + (row-8) + "']").addClass(color);
}
    
function mouseHold() {
    currentCol = this.getAttribute("data-col");
    
    if (currentCol != null && !$(this).hasClass("otherselected")) {
        isMouseDown = true;
        $(this).toggleClass("selected");
        isSelected = $(this).hasClass("selected");
    }
    return false;
}

function mouseDrag() {
    if (isMouseDown) {
        if (currentCol != null && currentCol === this.getAttribute("data-col") && !$(this).hasClass("otherselected")) {
            $(this).toggleClass("selected", isSelected);
        }
    }
}

function processSelected() {
    let insertTime = {};
    let group = [];
    
    for (let idx = 0; idx < 7; idx++) {
        insertTime[idx] = [];
    }
    
    for (let row = 0; row < 11; row++) {
        for (let col = 0; col < 7; col++) {
            if ($("#schedule [data-col='" + col + "'][data-row='" + row + "']").hasClass('selected')) {
                for (let check = 0; check < 7; check++) {
                    if (col === check) {
                        insertTime[col].push(row + 8);
                    }
                }
            }
        }
    }
    
    for (let ele in insertTime) {
        insertTime[ele].sort(function (a, b) { return a - b;});
    }

    for (let idx = 0; idx < 7; idx++) {
        let toggle = false;
        var start;
        for (let idx2 = 0; idx2 <= insertTime[idx].length - 1; idx2++) {
            if (!toggle) {
                start = insertTime[idx][idx2];
            }
            
            if (insertTime[idx][idx2] + 1 === insertTime[idx][idx2 + 1]) {
                toggle = true;
            }else {
                group.push({"day": idx, "start": start, "end": insertTime[idx][idx2]});
                toggle = false;
            }
        }
    }
    return group;
}
