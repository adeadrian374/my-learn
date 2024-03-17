## How To Set Config Zeppelin Notebook Fresh 

1. Access Table Managed HIVE ACID via Zeppelin notebook using spark.sql & pyspark 

note: - secara default Hive3 dalam managed table bersifat ACID, artinya table dari properties nya itu bersifat transactional

Environment

- Cloudera 3.x

- Rocky 8.7

- Hive 3.1.2

- Zeppelin 0.10.1

- Spark2 3.0.2

Step

1. Add config from spark2 config (Custom spark2-defaults & Custom spark2-thrift-sparkconf), agar dapat modify value from static config spark.sql to hive
   
   ```
   spark.sql.catalogImplementation=hive
   ```
   save & restart service spark2

2. perlu add jar external ke all-nodes(taruh di path selain /spark2/jars) & insert jar external kedalam interpreter spark2 pada bagian dependencies artifact, berikut list jar yg dibutuhkan;
   
   ```
   spark-acid_2.12-3.0.1.3.0.7110.0-81.jar
   calcite-core-1.32.0.jar
   ```
3. add property spark.hadoop.hive.vectorized.execution.enabled dan spark.sql.extensions di interpreter spark2, agar dapat read managed table hive using spark.sql
   
   ```
   spark.hadoop.hive.vectorized.execution.enabled=false
   spark.sql.extensions=com.qubole.spark.hiveacid.HiveAcidAutoConvertExtension
   ```
4. add property python & change value SparkR di interpreter spark2
  
   ```
   before
   zeppelin.R.cmd=R
   
   after
   zeppelin.R.cmd=/usr/bin/R
   PYSPARK_PYTHON=/usr/bin/python3
   PYSPARK_DRIVER_PYTHON=/usr/bin/python3
   ```
5. Create notebook baru dan pilih interpreter spark2 lalu test spark.sql & pyspark nya via zeppelin, untuk mengetahui apakah Managed table hive dengan table properties yang bersifat Acid(transactional) dapat berjalan atau tidak

   - create table managed hive using spark.sql from database default on hive
     
     ```
     input

     %spark2.pyspark

     spark.sql('CREATE TABLE heart_disease (id INT, name STRING, age INT, gender STRING)')

     output

     DataFrame[]
     ```
     jika output dari create table using spark.sql muncul seperti diatas maka tandanya managed table hive (creat) berhasil

   - Create DataFrame for load data to table using spark.sql

     ```
     input

     %spark2.pyspark
     columns = ["id", "name","age","gender"]

     # Create DataFrame 
     data = [(1, "James",30,"M"), (2, "Ann",40,"F"),
         (3, "Jeff",41,"M"),(4, "Jennifer",20,"F")]
     sampleDF = spark.sparkContext.parallelize(data).toDF(columns)

     check output

     %spark2.pyspark
     sampleDF.show()
     ```
     jika output dari DF berupa tabel dengan data yang lengkap maka dataframe berhasil dibuat

   - load data dataframe kedalam table existing 

     ```
     input

     %spark2.pyspark
     sampleDF.write.mode('overwrite').saveAsTable("heart_disease")

     output

     %spark2.pyspark
     spark.sql('select * from heart_disease').show()
     ```
     jika output dari table menampilkan data dari DF, maka write managed table hive using spark2 berhasil
     
   - Count data dari table existing
     
     ```
     input
     %spark2.pyspark
     spark.sql('select count (*) from heart_disease').show()
     ```

2. Access External Table HIVE NON ACID using spark.sql & pyspark

noted: apabila kondisi zeeppelin notebook dalam keadaan fresh,dan dicoba untuk create managed table hive using spark.sql, maka akan muncul eror seperti ini 

```
MetaException(message:Table testl2.emp failed strict managed table checks due to the following reason: Table is marked as a managed table but is not transactional.);
```
eror tersebut dikarenakan dari spark.sql membaca dan membuat table managed hive dimana secara default hive3 ketika melakukan managed table hive akan bersifat ACID (Transactional)

jika tetap ingin mencoba menghilangkan eror tsb, dan mencoba create table mungkin bisa dilakukan dengan opsi alternatif yaitu access external table hive non acid using spark.sql

## Solution

1. disable hive.strict.managed.tables dengan meng-unchecklist config dari Advanced hive-interactive-site & Advanced hive-site yang ada di service Hive

   ```
   hive.strict.managed.tables=false
   ```
2. Disable property on performance Enable Vectorization and Map Vectorization=false yang ada di service hive

   ```
   Enable Vectorization and Map Vectorization=false
   ```

   save & restart service hive

3. Add config from spark2 config (Custom spark2-defaults & Custom spark2-thrift-sparkconf), agar dapat modify value from static config spark.sql to hive
   
   ```
   spark.sql.catalogImplementation=hive
   ```
   save & restart service spark2



