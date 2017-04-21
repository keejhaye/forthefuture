memwatch = require('memwatch')

memwatch.on('leak', function(info) {
    // look at info to find out about what might be leaking
    console.log('============= MEMWATCH ON LEAK ============\n',info)
})

memwatch.on('stats', function(stats) {
    // do something with post-gc memory usage stats
    console.log('============= MEMWATCH STATS ============\n', stats)
})

var myStream = fs.createWriteStream('/logs/logmem.log')

setInterval( function() {
    var t = new Date().getTime();

    var memUsage = process.memoryUsage()
    var str = t+';heapUsed;'+memUsage.heapUsed+';0;0;0\n'+
                t+';heapTotal;'+memUsage.heapTotal+';0;0;0\n'+
                t+';rss;'+memUsage.rss+';0;0;0\n'
    myStream.write( str )
    console.log( str )
}, 3000 )