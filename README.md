# ConverterBundle
Alternative to Symfony/Serializer to convert complex data types using Yaml configuration.

## Event handling order (priority)
1. CheckInputTypeSubscriber (10000)
    - Ensure the input type is supported
2. BehaviorsHandlerSubscriber (1200)
    - Attach mapping configurations from behaviors
3. PropertiesExtractorSubscriber (1000)
    - Extract properties from the input data based on the mapping configuration
4. AutoMappingExtractorSubscriber (900)
    - Automatically extract remaining properties from the input if the auto_mapping option is enabled
5. TransformerSubscriber (800)
    - Transform the extracted properties using the transformer configurations
6. OutputCreatorSubscriber (400)
    - Create the output object, either by using the constructor and the available properties or by creating a new
    instance without the constructor if the hydrate_object option is enabled.
7. HydratorSubscriber (200)
    - Hydrate the output object using the transformed properties if the hydrate_object option is enabled.
8. PropertiesSetterSubscriber (0)
    - Set the properties on the output object by using the PropertyAccessor
9. CheckRemainingPropertiesSubscriber (-10000)
    - Check if there are remaining properties in the input data that have not been processed
