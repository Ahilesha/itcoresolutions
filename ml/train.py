import pandas as pd
from prophet import Prophet

# Load CSV exported from Laravel
df = pd.read_csv('storage/app/private/ml/product_demand.csv')

# Rename columns for Prophet
df = df.rename(columns={
    'date': 'ds',
    'total_qty': 'y'
})

# Convert date column
df['ds'] = pd.to_datetime(df['ds'])

# Train model
model = Prophet()
model.fit(df)

# Create future dates (next 7 days)
future = model.make_future_dataframe(periods=7)

# Predict demand
forecast = model.predict(future)

# Save predictions
result = forecast[['ds', 'yhat']]
result.to_csv('predictions_products.csv', index=False)

print("Forecast generated successfully")