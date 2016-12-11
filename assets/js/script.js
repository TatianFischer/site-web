$(function () {
    var Sites = [
        {
            "lien"          : "porfolio/02-CSS/index.html",
            "image"         : "assets/images/site-docteur-who.png",
            "alternative"   : "Site Doctor Who",
            "methode"       : "Site développé en HTML5 et CSS3 (scroll avec un plugin jQuery)"
            
        },
        
        {
            "lien"          : "porfolio/04-HTMLetCSSAvancés/index.html",
            "image"         : "assets/images/site-magistere.png",
            "alternative"   : "Site Magistère de Physicochimie d'Orsay",
            "methode"       : "Site adaptatif en HTML5 et CSS3."
        },
        
        {
            "lien"          : "porfolio/05-Atelier-Integration/index.html",
            "image"         : "assets/images/site-bayatropic.png",
            "alternative"   : "Site Baya Tropic",
            "methode"       : "Intégration en utilisant l'outil Extract for Brackets (HTLM5 et CSS3)."
        },
        
        {
            "lien"          : "porfolio/06-Atelier-Responsive/index.html",
            "image"         : "assets/images/site-alsace-noel.png",
            "alternative"   : "Site Alsace à Noël",
            "methode"       : "Utilisation de Bootstrap."
        },
        
        {
            "lien"          : "porfolio/07-Evaluation/index.html",
            "image"         : "assets/images/site-licornes.png",
            "altenative"    : "Site sur les licornes",
            "methode"       : "Evaluation : intégration en temps 4 heures à partir d'une image et d'un cahier des charges."
        },
        
        {
            "lien"          : "porfolio/10-Atelier-Pratique/SiteWebApple.html",
            "image"         : "assets/images/site-apple.png",
            "alternative"   : "Site Apple",
            "methode"       : "Intégration en utilisant l'outil extract for Brackets et Bootstrap."
        }
    ];
    for(var i = 0 ; i < Sites.length ; i++){
        $("<div>").addClass("col-md-4 col-sm-6")
        .append($("<a>").attr({'href': Sites[i].lien, 'target': '_blank'})
                .append($("<img>").attr({'src':Sites[i].image, 'alt' : Sites[i].alternative})))
        .append($("<p>").text(Sites[i].methode))
        .appendTo($('main'));
    }
    
});

/*
            
                            
            */