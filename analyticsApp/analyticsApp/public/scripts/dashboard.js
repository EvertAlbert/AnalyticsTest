const initDashboard = () => {
    loadProductChart();
    loadLangChart();
    loadPageVisitChart();
    loadAgeChart();
    loadActivityChart();
};


document.addEventListener(`DOMContentLoaded`, initDashboard());
