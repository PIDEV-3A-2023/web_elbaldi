var padding = {top:0, right:0, bottom:0, left:0},
            w = 500 - padding.left - padding.right,
            h = 500 - padding.top  - padding.bottom,
            r = Math.min(w, h)/2,
            rotation = 0,
            oldrotation = 0,
            picked = 100000,
            oldpick = [],
            color = d3.scale.category20();//category20c()
            //randomNumbers = getRandomNumbers();

        //http://osric.com/bingo-card-generator/?title=HTML+and+CSS+BINGO!&words=padding%2Cfont-family%2Ccolor%2Cfont-weight%2Cfont-size%2Cbackground-color%2Cnesting%2Cbottom%2Csans-serif%2Cperiod%2Cpound+sign%2C%EF%B9%A4body%EF%B9%A5%2C%EF%B9%A4ul%EF%B9%A5%2C%EF%B9%A4h1%EF%B9%A5%2Cmargin%2C%3C++%3E%2C{+}%2C%EF%B9%A4p%EF%B9%A5%2C%EF%B9%A4!DOCTYPE+html%EF%B9%A5%2C%EF%B9%A4head%EF%B9%A5%2Ccolon%2C%EF%B9%A4style%EF%B9%A5%2C.html%2CHTML%2CCSS%2CJavaScript%2Cborder&freespace=true&freespaceValue=Web+Design+Master&freespaceRandom=false&width=5&height=5&number=35#results

        var data = [
                           
        {"label":"500 Points", "value":1, "question":"Good ! you have won  !"},//semi-colon
          
          {"label":"200 Points", "value":2, "question":"Nice ! "},//semi-colon
          
          {"label":"Unwinnable !", "value":3, "question":" you will win next time :()"},//semi-colon
          
          {"label":"Lost", "value":4, "question":"HAHAHAHA "},//semi-colon
          
          {"label":"flat !", "value":5, "question":"You lost. :("},//semi-colon
          
          {"label":"2000 Points", "value":6, "question":"Quelle chance ! ."},//semi-colon
          
          {"label":"1000 points", "value":7, "question":"Il est mignon n'est-ce pas ?"},//semi-colon
            
            {"label":"Zut !", "value":8, "question":"Bon bah c'est raté :()"},//semi-colon 
              
              {"label":"Sac", "value":9, "question":"Félecitation !"},//semi-colon
                
                {"label":"Rien", "value":10, "question":"Désolé, c'est raté :()"},//semi-colon
                  
                  {"label":"Oups", "value":11, "question":"Aïe, c'est raté !"},//semi-colon
                    
                    {"label":"Chachia", "value":12, "question":"Félicitation!"},//semi-colon
                      
                      {"label":"5000Points", "value":13, "question":"Miam miam  :)"}
        
        
        ];


        var svg = d3.select('#chart')
            .append("svg")
            .data([data])
            .attr("width",  w + padding.left + padding.right)
            .attr("height", h + padding.top + padding.bottom);

        var container = svg.append("g")
            .attr("class", "chartholder")
            .attr("transform", "translate(" + (w/2 + padding.left) + "," + (h/2 + padding.top) + ")");

        var vis = container
            .append("g");
            
        var pie = d3.layout.pie().sort(null).value(function(d){return 1;});

        // declare an arc generator function
        var arc = d3.svg.arc().outerRadius(r);

        // select paths, use arc generator to draw
        var arcs = vis.selectAll("g.slice")
            .data(pie)
            .enter()
            .append("g")
            .attr("class", "slice");
            

        arcs.append("path")
            .attr("fill", function(d, i){ return color(i); })
            .attr("d", function (d) { return arc(d); });

        // add the text
        arcs.append("text").attr("transform", function(d){
                d.innerRadius = 0;
                d.outerRadius = r;
                d.angle = (d.startAngle + d.endAngle)/2;
                return "rotate(" + (d.angle * 180 / Math.PI - 90) + ")translate(" + (d.outerRadius -10) +")";
            })
            .attr("text-anchor", "end")
            .text( function(d, i) {
                return data[i].label;
            });

        container.on("click", spin);


        function spin(d){
            var lastSpinTime = localStorage.getItem('lastSpinTime');
            var currentTime = new Date().getTime(); 
            var i=localStorage.getItem('i');
            if (!i)
            localStorage.setItem('i', 0);
            if(i==2)
            {
            if (!lastSpinTime) {
                localStorage.setItem('lastSpinTime', currentTime);
            } else {
                var timeDiff = currentTime - lastSpinTime;
                 
                if (timeDiff >= 24 * 60 * 60 * 1000) { 
                    localStorage.setItem('lastSpinTime', currentTime);
                    localStorage.setItem('i', 0);
                } else {
                    alert('Sorry,Come back tomorrow.');
                    return;
                }
            }
        }
            i++;
            localStorage.setItem('i', i);
            container.on("click", null);

            //all slices have been seen, all done
            console.log("OldPick: " + oldpick.length, "Data length: " + data.length);
            if(oldpick.length == data.length){
                console.log("done");
                container.on("click", null);
                return;
            }

            var  ps       = 360/data.length,
                 pieslice = Math.round(1440/data.length),
                 rng      = Math.floor((Math.random() * 1440) + 360);
                
            rotation = (Math.round(rng / ps) * ps);
            
            picked = Math.round(data.length - (rotation % 360)/ps);
            picked = picked >= data.length ? (picked % data.length) : picked;


            if(oldpick.indexOf(picked) !== -1){
                d3.select(this).call(spin);
                return;
            } else {
                oldpick.push(picked);
            }

            rotation += 90 - Math.round(ps/2);
            
          // On fait tourner la roue
            vis.transition()
                .duration(6000)
                .attrTween("transform", rotTween)
                // Actions à lancer à l'arrêt de la roue
                .each("end", function(){

                    //mark question as seen
                    /*d3.select(".slice:nth-child(" + (picked + 1) + ") path")
                        .attr("fill", "purple");*/
 
                    //populate question
                    d3.select("#question p")
                        .text(data[picked].question);

                    oldrotation = rotation;
                
                    container.on("click", spin);
              
                   
                    let value = data[picked].value;
                    fetch('/event/Roue/1?value=' + encodeURIComponent(value))
                      .then(response => response.text())
                      .then(data => console.log(data))
                      .catch(error => console.error(error));
                });
        }

        //make arrow
        svg.append("g")
            .attr("transform", "translate(" + (w + padding.left + padding.right) + "," + ((h/2)+padding.top) + ")")
            .append("path")
            .attr("d", "M-" + (r*.15) + ",0L0," + (r*.05) + "L0,-" + (r*.05) + "Z")
            .style({"fill":"red"});

        //Cercle centre "Lancer"
        container.append("circle")
            .attr("cx", 0)
            .attr("cy", 0)
            .attr("r", 70) // Rayon du cercle
            .style({"fill":"white","cursor":"pointer","background": "#CCEAF1"});

        //Texte bouton "Lancer"
        container.append("text")
            .attr("x", 0)
            .attr("y", 8)
            .attr("text-anchor", "middle")
            .text("Lancer")
            .style({"font-weight":"bold", "font-size":"22px"});
        
        
        function rotTween(to) {
          var i = d3.interpolate(oldrotation % 360, rotation);
          return function(t) {
            return "rotate(" + i(t) + ")";
          };
        }
        
  
        function getRandomNumbers(){
            var array = new Uint16Array(1000);
            var scale = d3.scale.linear().range([360, 1440]).domain([0, 100000]);

            if(window.hasOwnProperty("crypto") && typeof window.crypto.getRandomValues === "function"){
                window.crypto.getRandomValues(array);
                console.log("works");
            } else {
                //no support for crypto, get crappy random numbers
                for(var i=0; i < 1000; i++){
                    array[i] = Math.floor(Math.random() * 100000) + 1;
                }
            }

            return array;
        }