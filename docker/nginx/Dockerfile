FROM nginx:1.24-alpine

# Install required packages
RUN apk add --no-cache curl

# Create app directory
RUN mkdir -p /app

# Copy the nginx config file
COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Set working directory
WORKDIR /app

# Health check
HEALTHCHECK --interval=30s --timeout=3s \
    CMD curl -f http://localhost/ || exit 1
