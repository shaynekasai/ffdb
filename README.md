FFDB - Test Example 1
====================

So, I'm playing around with forest fire data from the BC government which is available [here](http://www.data.gov.bc.ca/).

This is just a simple example where I wanted to test a few technologies together:

- PHP
- MongoDB
- Heatmaps
- Open data

Eventually I would like to perform some calculations around each point, so I threw in parallel.js to support some
worker type threading calculations if needed.

This isn't really meant to be useful but a proof of concept. I also created an importer that takes CSV data and pushes it into MongoDB.

TODO:
- Streamline import.php
- Add weight to points
- Maybe add some other geo information
- I'd like to represent this data in other ways too, not just heat maps