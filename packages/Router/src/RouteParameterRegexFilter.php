<?php

namespace Pascal\Router;

class RouteParameterRegexFilter implements RouteParameterFilterInterface
{

    /**
     * @param array $mappedRoute
     *
     * @return array
     */
    public function filter(array $mappedRoute): array
    {
        $mappedParameters = [];

        foreach ($mappedRoute as $key => $value) {
            list($parameterKey, $parameterRegex) = $this->matchParameter($key);

            if (!$parameterKey) {
                continue;
            }

            if ($parameterRegex && !preg_match("/$parameterRegex/", $value)) {
                throw new CouldNotParseRouteException(
                    sprintf('The value %s did not comply to the regex constraint %s.', $value, $parameterRegex)
                );
            }

            $mappedParameters[$parameterKey] = $value;
        }

        return $mappedParameters;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    private function matchParameter(string $key): array
    {
        preg_match('/{([a-zA-Z0-9_]+)(:(.*))?}/', $key, $parameterMatch);

        return [
            $parameterMatch[1] ?? null,
            $parameterMatch[3] ?? null
        ];
    }
}
