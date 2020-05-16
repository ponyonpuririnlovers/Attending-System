var gitGraph = new GitGraph({
   template: "blackarrow",
   mode: "compact",
   orientation: "horizontal",
   reverseArrow: true
});

var master = gitGraph.branch("master").commit().commit();
var develop = gitGraph.branch("eda1").commit();
master.commit();
develop.commit().commit();
develop.merge(master);