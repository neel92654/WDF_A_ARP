var allEvents = [];
var filteredEvents = [];
var currentPage = 1;
var perPage = 5;

function loadEvents() {
    fetch("../data/events.json")
        .then(function (response) {
            if (!response.ok) throw new Error("Could not load events.");
            return response.json();
        })
        .then(function (data) {
            allEvents = data;
            applyFilters();
        })
        .catch(function () {
            document.getElementById("eventStatus").textContent = "Unable to load event data.";
        });
}

function applyFilters() {
    var search = document.getElementById("eventSearch").value.toLowerCase();
    var category = document.getElementById("eventFilter").value;
    var sort = document.getElementById("eventSort").value;

    filteredEvents = allEvents.filter(function (event) {
        var matchesText = event.name.toLowerCase().includes(search) || event.venue.toLowerCase().includes(search);
        var matchesCategory = category === "all" || event.category === category;
        return matchesText && matchesCategory;
    });

    filteredEvents.sort(function (a, b) {
        if (sort === "name") return a.name.localeCompare(b.name);
        if (sort === "dateDesc") return b.date.localeCompare(a.date);
        return a.date.localeCompare(b.date);
    });

    currentPage = 1;
    renderEvents();
}

function renderEvents() {
    var body = document.getElementById("eventTableBody");
    body.innerHTML = "";
    var start = (currentPage - 1) * perPage;
    var pageItems = filteredEvents.slice(start, start + perPage);

    pageItems.forEach(function (event) {
        var row = document.createElement("tr");
        row.innerHTML = "<td>" + event.name + "</td><td>" + event.date + "</td><td>" + event.venue + "</td><td>" + event.category + "</td>";
        body.appendChild(row);
    });

    document.getElementById("eventStatus").textContent = filteredEvents.length + " event(s) found.";
    renderPagination();
}

function renderPagination() {
    var box = document.getElementById("eventPagination");
    box.innerHTML = "";
    var pages = Math.ceil(filteredEvents.length / perPage);
    for (var i = 1; i <= pages; i++) {
        var button = document.createElement("button");
        button.textContent = i;
        button.setAttribute("type", "button");
        button.className = "btn btn-sm " + (i === currentPage ? "btn-primary active" : "btn-outline-primary");
        button.onclick = (function (page) { return function () { currentPage = page; renderEvents(); }; })(i);
        box.appendChild(button);
    }
}

document.getElementById("eventSearch").addEventListener("input", applyFilters);
document.getElementById("eventFilter").addEventListener("change", applyFilters);
document.getElementById("eventSort").addEventListener("change", applyFilters);
loadEvents();
