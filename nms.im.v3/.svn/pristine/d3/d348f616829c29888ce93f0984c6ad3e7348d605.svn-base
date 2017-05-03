
var SequelizeAuto = require('sequelize-auto')
var auto = new SequelizeAuto('db_imv3', 'root', 'qwerty321', {
  host: 'MES-SERVER',
  port : 50000,
  timeStamps : false
});

// var auto = new Sequelize('db_imv3', 'root', '', {
//   host: 'localhost',
//   port : 3306,
//   pool: {
//     max: 10,
//     min: 0,
//     idle: 10000
//   },
//   benchmark : true,
//   logging: false
// })

auto.run(function (err) {
  if (err) throw err;

  console.log(auto.tables); // table list
  console.log(auto.foreignKeys); // foreign key list
});